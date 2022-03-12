<div class="form-group border g-0 shadow-lg" style="background-color: rgba(0,0,0,0.15);">
    <label for="warehouses_list"><b>по складам</b></label>
    <div class="col-sm p-sm-1" id="warehouses_list">
        @foreach($warehouses as $warehouse)
            <div class="custom-control custom-checkbox" style="border: 1px groove white; background-color: rgba(255,255,255,0.1);">
                @if (
                    isset($_REQUEST['filter']['warehouses']) &&
                    ($_REQUEST['filter']['warehouses'] !== '') &&
                    in_array($warehouse['id'], $_REQUEST['filter']['warehouses'])
                    )
                    <input type="checkbox" class="col-1 custom-control-input" name="filter[warehouses][]" id="warehouse-{{$warehouse['id']}}" value="{{$warehouse['id']}}" checked>
                @else
                    <input type="checkbox" class="col-1 custom-control-input" name="filter[warehouses][]" id="warehouse-{{$warehouse['id']}}" value="{{$warehouse['id']}}">
                @endif
                <label class="col-11 custom-control-label p-1 m-1 text-left text-break"
                       for="warehouse-{{$warehouse['id']}}"
                       style="font-size: .7rem;">
                    <strong>{{$warehouse['name']}}</strong>
                </label>
            </div>
        @endforeach
    </div>
</div>
