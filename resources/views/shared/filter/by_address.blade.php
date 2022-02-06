<div class="form-group border g-0 shadow-lg" style="background-color: rgba(0,0,0,0.15);">
    <label for="address"><b>по адресу</b></label>
    @if (isset($_REQUEST['filter']['address']) && ($_REQUEST['filter']['address'] !== ''))
        <input type="text" class="form-control" id="address" name="filter[address]" value="{{$_REQUEST['filter']['address']}}" style="background-color: rgba(255,255,255,0.3);">
    @else
        <input type="text" class="form-control" id="address" name="filter[address]" style="background-color: rgba(255,255,255,0.3);">
    @endif
</div>
