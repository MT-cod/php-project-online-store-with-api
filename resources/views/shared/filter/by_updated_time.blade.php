<div class="form-group border g-0 shadow-lg" style="background-color: rgba(0,0,0,0.15);">
    <label><b>по времени последнего изменения данных</b></label>
    <div class="input-group" style="background-color: rgba(0,0,0,0.15);">
        <div class="input-group-prepend">
            <span class="input-group-text" style="background-color: rgba(255,255,255,0.1);">с</span>
        </div>
        @if (isset($_REQUEST['filter']['update_min']) && ($_REQUEST['filter']['update_min'] !== ''))
            <input type="date" class="form-control text-center" name="filter[update_min]" value="{{$_REQUEST['filter']['update_min']}}" style="background-color: rgba(255,255,255,0.4);">
        @else
            <input type="date" class="form-control text-center" name="filter[update_min]" style="background-color: rgba(255,255,255,0.4);">
        @endif
    </div>
    <div class="input-group" style="background-color: rgba(0,0,0,0.15);">
        <div class="input-group-prepend">
            <span class="input-group-text" style="background-color: rgba(255,255,255,0.1);">по</span>
        </div>
        @if (isset($_REQUEST['filter']['update_max']) && ($_REQUEST['filter']['update_max'] !== ''))
            <input type="date" class="form-control text-center" name="filter[update_max]" value="{{$_REQUEST['filter']['update_max']}}" style="background-color: rgba(255,255,255,0.4);">
        @else
            <input type="date" class="form-control text-center" name="filter[update_max]" style="background-color: rgba(255,255,255,0.4);">
        @endif
    </div>
</div>
