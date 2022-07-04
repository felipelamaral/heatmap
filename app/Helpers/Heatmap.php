<?php

namespace App\Helpers;

class Heatmap
{
    //variavel que define volume = value
            //value : quantidade total em reais
            
    //variavel que define cor = truckage
        //truckage : quantidade total de fretes

    public static function build($data, $width, $height, $padding, $sizeby, $colorby) {
        $width -= $padding;
        $height -= $padding;

        $container = [];
        $container['x0'] = $padding/2;
        $container['y0'] = $padding/2;
        $container['x1'] = $width;
        $container['y1'] = $height;

        $squarified = self::startSquarify($data, $container, $sizeby);
        $squarifiedWithColors = self::attrColor($squarified, $colorby);

        return $squarifiedWithColors;
    }

    public static function startSquarify($data, $container, $sizeby) {
        $input = [];

        //x0, y0 superior esquerdo
        //x1, y1 inferior direito
        $input['x0'] = $container['x0'];
        $input['y0'] = $container['y0'];
        $input['x1'] = $container['x1'];
        $input['y1'] = $container['y1'];
        $input['children'] = $data;

        $input = (object) $input;

        if(!$input->children) {
            return [$input];
        } else {
            $childrenWithProportion = self::calcProportion($input->children, self::getArea($input), $sizeby); 
            $squarified = self::squarify($childrenWithProportion, [], $input, []);

            return $squarified;
        }
    }

    public static function getArea($rect) { return ($rect->x1 - $rect->x0)*($rect->y1 - $rect->y0); }    

    public static function calcProportion($data, $area, $sizeby) {
        $dataLength = count($data);
        $dataSum = 0;

        for ($i = 0; $i < $dataLength; $i++) {
            $dataSum += $data[$i][$sizeby];
        }

        $multiplier = $area / $dataSum;

        $elementResult;

        for ($j = 0; $j < $dataLength; $j++) {
            $data[$j]['proportionValue'] = $data[$j][$sizeby] * $multiplier;
        }

        usort($data, function ($a, $b) { return strcmp($b['proportionValue'], $a['proportionValue']); });

        return $data;
    }

    public static function squarify($data, $currentRow, $rect, $stack) {
        while (true) {
            if (count($data) === 0) {
                $newCoordinates = self::getCoordinates($currentRow, $rect);
                $newStack = array_merge($stack, $newCoordinates);
                return $newStack;
            }

            $width = self::getShortestEdge($rect);
            $nextDatum = (object) $data[0];
            $restData = array_slice($data, 1);           

            if(self::doesAddingToRowImproveAspectRatio($currentRow, $nextDatum, $width)) {
                $data = $restData;
                $currentRow = array_merge($currentRow, [$nextDatum]);
                $rect = $rect;
                $stack = $stack;    

            } else {
                $currentRowLength = count($currentRow);
                $valueSum = 0;

                for ($i = 0; $i < $currentRowLength; $i++) {
                    $valueSum += $currentRow[$i]->proportionValue;
                }

                $newContainer = self::cutArea($rect, $valueSum);
                $newCoordinates = self::getCoordinates($currentRow, $rect);
                $newStack = array_merge($stack, $newCoordinates);
                $currentRow = [];
                $rect = $newContainer;
                $stack = $newStack;
            }
        }
    }

    public static function getShortestEdge($input) {
        $container = self::rectToContainer($input);
        $container = (object) $container;
        $result = min($container->width, $container->height);

        return $result;
    }

    public static function rectToContainer($rect) {
        $rect = (object) $rect;
        return [
            'xOffset' => $rect->x0,
            'yOffset' => $rect->y0,
            'width' => $rect->x1 - $rect->x0,
            'height' => $rect->y1 - $rect->y0
        ];
    }

    public static function containerToRect($container) {
        $container = (object) $container;
        return [
          'x0' => $container->xOffset,
          'y0' => $container->yOffset,
          'x1' => $container->xOffset + $container->width,
          'y1' => $container->yOffset + $container->height
        ];
    }

    public static function doesAddingToRowImproveAspectRatio($currentRow, $nextDatum, $length) {        
        if(count($currentRow) === 0) {
            return true;
        } else {
            $newRow = array_merge($currentRow, [$nextDatum]);
            $currentMaxAspectRatio = self::calculateMaxAspectRatio($currentRow, $length);
            $newMaxAspectRatio = self::calculateMaxAspectRatio($newRow, $length);

            return $currentMaxAspectRatio >= $newMaxAspectRatio;
        }        
    }

    public static function calculateMaxAspectRatio($row, $length) {
        $rowLength = count($row);

        if ($rowLength === 0) {
            throw new Error("Input " + $row + " is empty");
        } else {
            $minArea = INF;
            $maxArea = -INF;
            $sumArea = 0;
            
            for($i = 0; $i < $rowLength; $i++) {
                $area = $row[$i]->proportionValue;
                
                if($area < $minArea) { $minArea = $area; }
                if($area > $maxArea) { $maxArea = $area; }

                $sumArea += $area;
            }

            $result = max(
            ($length**2 * $maxArea) / $sumArea**2,
            $sumArea**2 / ($length**2 * $minArea)
            );
            
            return $result;
        }
    }

    public static function cutArea($rect, $area) {
        $rectContainer = self::rectToContainer($rect);
        $rectContainer = (object) $rectContainer;
        $width = $rectContainer->width;
        $height = $rectContainer->height;
        $xOffset = $rectContainer->xOffset;
        $yOffset = $rectContainer->yOffset;

        if ($width >= $height) {
            $areaWidth = $area / $height;
            $newWidth = $width - $areaWidth;
            $container = [
                'xOffset' => $xOffset + $areaWidth,
                'yOffset' => $yOffset,
                'width'=> $newWidth,
                'height' => $height
            ];
            return self::containerToRect($container);
        } else {
            $areaHeight = $area / $width;
            $newHeight = $height - $areaHeight;
            $container = [
                'xOffset' => $xOffset,
                'yOffset' => $yOffset + $areaHeight,
                'width' => $width,
                'height' => $newHeight
            ];
            return self::containerToRect($container);
        }
    }

    public static function getCoordinates($row, $rect) {        
        $container = self::rectToContainer($rect);
        $container = (object) $container;
        $width = $container->width;
        $height = $container->height;
        $xOffset = $container->xOffset;
        $yOffset = $container->yOffset;
        $rowLength = count($row);
        $valueSum = 0;

        for ($i = 0; $i < $rowLength; $i++) {
          $valueSum += $row[$i]->proportionValue;
        }
        $areaWidth = $valueSum / $height;
        $areaHeight = $valueSum / $width;
        $subXOffset = $xOffset;
        $subYOffset = $yOffset;
        $coordinates = [];
        if ($width >= $height) {
          for ($i = 0; $i < $rowLength; $i++) {
            $num = $row[$i];
            $y1 = $subYOffset + $num->proportionValue / $areaWidth;
            $rectangle = (object) [
              'x0' => $subXOffset,
              'y0' => $subYOffset,
              'x1' => $subXOffset + $areaWidth,
              'y1' => $y1
            ];

            $nextCoordinate = (object) array_merge((array)$num, (array)$rectangle);
            $subYOffset = $y1;
            $coordinates = array_merge($coordinates,[$nextCoordinate]);
          }
          return $coordinates;
        } else {
          for ($i = 0; $i < $rowLength; $i++) {
            $num = $row[$i];
            $x1 = $subXOffset + $num->proportionValue / $areaHeight;
            $rectangle = [
              'x0' => $subXOffset,
              'y0' => $subYOffset,
              'x1' => $x1,
              'y1' => $subYOffset + $areaHeight
            ];
            $nextCoordinate = (object) array_merge((array)$num, (array)$rectangle);
            $subXOffset = $x1;
            $coordinates = array_merge($coordinates,[$nextCoordinate]);
          }
          return $coordinates;
        }
    }

    public static function attrColor($nodes, $colorby) {
        $currentMax = NULL;
        foreach($nodes as $node)
        {
            if ($node->$colorby >= $currentMax)
            {
                $currentMax = $node->$colorby;
            }
        }

        foreach($nodes as $node) {
            $percent = ($node->$colorby*100)/$currentMax;

            switch (true) {
                case ($percent <= 20):
                    $node->color = "#FE9D52";
                    break;
                case ($percent > 20 && $percent <= 50):
                    $node->color = "#FFCEA9";
                    break;
                case ($percent > 50 && $percent <= 70):
                    $node->color = "#9ECBED";
                    break;
                case ($percent > 70 && $percent <= 90):
                    $node->color = "#3C97DA";
                    break;
                case ($percent > 90):
                    $node->color = "#2A6A99";
                    break;
            }
        }

        return $nodes;

    }
}