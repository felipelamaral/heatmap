<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Helpers\Heatmap;

class HeatmapIndex extends Component
{
    public $data;
    public $padding = 0;
    public $width = 1200;
    public $height = 800;

    public $sizeby = "value";
    public $colorby = "truckage";

    public function __construct() {
        //simula requisição api
        $this->data = json_decode(file_get_contents(PUBLIC_PATH().'\api.json'), true);
        $this->data = $this->data['unidades'];
    }

    public function render()
    {
        $this->data = Heatmap::build($this->data, $this->width, $this->height, $this->padding, $this->sizeby, $this->colorby);

        return view('livewire.heatmap-index');
    }
}
