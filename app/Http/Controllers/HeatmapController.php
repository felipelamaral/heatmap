<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Heatmap;

class HeatmapController extends Controller
{
    protected $data;

    public function __construct() {
        //simula requisição api
        $this->data = json_decode(file_get_contents(PUBLIC_PATH().'\api.json'), true);
        $this->data = $this->data['unidades'];
    }

    public function index() {
        $nodes = Heatmap::build($this->data);

        return view('index', [ 
            'data' => $nodes
        ]);
    }

}
