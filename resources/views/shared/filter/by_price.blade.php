<div class="form-group border g-0 shadow-lg" style="background-color: rgba(0,0,0,0.15);">
    <label><b>по цене</b></label>
    <div class="input-group" style="background-color: rgba(0,0,0,0.15);">
        <div class="input-group-prepend">
            <span class="input-group-text" style="background-color: rgba(255,255,255,0.1);">min</span>
        </div>
        @if (isset($_REQUEST['filter']['price_min']) && ($_REQUEST['filter']['price_min'] !== ''))
            <input type="text" class="form-control" name="filter[price_min]" value="{{$_REQUEST['filter']['price_min']}}" style="background-color: rgba(255,255,255,0.4);">
        @else
            <input type="text" class="form-control" name="filter[price_min]" style="background-color: rgba(255,255,255,0.4);">
        @endif
        <div class="input-group-prepend">
            <span class="input-group-text" style="background-color: rgba(255,255,255,0.1);">max</span>
        </div>
        @if (isset($_REQUEST['filter']['price_max']) && ($_REQUEST['filter']['price_max'] !== ''))
            <input type="text" class="form-control" name="filter[price_max]" value="{{$_REQUEST['filter']['price_max']}}" style="background-color: rgba(255,255,255,0.4);">
        @else
            <input type="text" class="form-control" name="filter[price_max]" style="background-color: rgba(255,255,255,0.4);">
        @endif
    </div>
</div>
