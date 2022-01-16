<div class="form-group border g-0 shadow-lg" style="background-color: rgba(0,0,0,0.15);">
    <label><b>по времени занесения в базу</b></label>
    <div class="input-group" style="background-color: rgba(0,0,0,0.15);">
        <div class="input-group-prepend">
            <span class="input-group-text" style="background-color: rgba(255,255,255,0.1);">с</span>
        </div>
        @if (isset($_REQUEST['filter']['create_min']) && ($_REQUEST['filter']['create_min'] !== ''))
            <input type="date" class="form-control text-center" name="filter[create_min]" value="{{$_REQUEST['filter']['create_min']}}" style="background-color: rgba(255,255,255,0.4);">
        @else
            <input type="date" class="form-control text-center" name="filter[create_min]" style="background-color: rgba(255,255,255,0.4);">
        @endif
    </div>
    <div class="input-group" style="background-color: rgba(0,0,0,0.15);">
        <div class="input-group-prepend">
            <span class="input-group-text" style="background-color: rgba(255,255,255,0.1);">по</span>
        </div>
        @if (isset($_REQUEST['filter']['create_max']) && ($_REQUEST['filter']['create_max'] !== ''))
            <input type="date" class="form-control text-center" name="filter[create_max]" value="{{$_REQUEST['filter']['create_max']}}" style="background-color: rgba(255,255,255,0.4);">
        @else
            <input type="date" class="form-control text-center" name="filter[create_max]" style="background-color: rgba(255,255,255,0.4);">
        @endif
    </div>
</div>
