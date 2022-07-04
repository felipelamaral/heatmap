<div class="container-fluid">
    
    <div class="jumbotron jumbotron-fluid" style="width: {{ $width }}px;">
        <div class="container">
            <h1 class="display-4 font-weight-bold">Challenge HeatMap</h1>
        </div>
    </div>    

    <div class="content row">

        <div class="col-12">
            <div class="filter row" style="width: {{ $width }}px;">
                <div class="col-3">
                    <label for="basic-url">Change Width</label>
                    <div class="input-group mb-3">
                        <input type="number" class="form-control" wire:model='width'>
                    </div>
                </div>
                <div class="col-3">
                    <label for="basic-url">Change Height</label>
                    <div class="input-group mb-3">
                        <input type="number" class="form-control" wire:model='height'>
                    </div>
                </div>
                <div class="col-3">
                    <label for="basic-url">Size by</label>
                    <div class="input-group mb-3">
                        <select class="custom-select" id="sizeby" wire:model='sizeby'>
                            <option value="value" selected>Value</option>
                            <option value="truckage">Truckage</option>
                          </select>
                    </div>
                </div>
                <div class="col-3">
                    <label for="basic-url">Colors by</label>
                    <div class="input-group mb-3">
                        <select class="custom-select" id="colorby" wire:model='colorby'>
                            <option value="value">Value</option>
                            <option value="truckage" selected>Truckage</option>
                          </select>
                    </div>
                </div>
            </div>
    
            <div class="canvas" style="width: {{ $width }}px; height: {{ $height }}px">
                @foreach ($data as $d)
                    <div class="item" style="
                                background-color: {{ $d->color }};
                                left:{{ $d->x0 }}px; 
                                top:{{ $d->y0 }}px; 
                                width:{{ $d->x1 - $d->x0 }}px; 
                                height: {{ $d->y1 - $d->y0 }}px;
                            ">
                        {{ $d->name }} <br/> R${{ $d->value }} <br/> {{ $d->truckage }}
                    </div>
                @endforeach
            </div>

            <div>
                <ul class="legend">
                    <p class="lead font-weight-normal">Colors legend:</p>
                    <li><span class="s1"></span> menor que 20% </li>
                    <li><span class="s2"></span> entre 20% e 50%</li>
                    <li><span class="s3"></span> entre 50% e 70%</li>
                    <li><span class="s4"></span> entre 70% e 90%</li>
                    <li><span class="s5"></span> maior que 90%</li>
                </ul>
            </div>
            
        </div>
        
    </div>
    
    
</div>
