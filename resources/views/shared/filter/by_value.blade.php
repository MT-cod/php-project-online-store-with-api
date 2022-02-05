<div class="form-group border g-0 shadow-lg" style="background-color: rgba(0,0,0,0.15);">
    <label for="value"><b>по значению</b></label>
    @if (isset($_REQUEST['filter']['value']) && ($_REQUEST['filter']['value'] !== ''))
        <input type="text" class="form-control" id="value" name="filter[value]" value="{{$_REQUEST['filter']['value']}}" style="background-color: rgba(255,255,255,0.3);">
    @else
        <input type="text" class="form-control" id="value" name="filter[value]" style="background-color: rgba(255,255,255,0.3);">
    @endif
</div>
