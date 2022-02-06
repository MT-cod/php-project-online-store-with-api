<div class="form-group border g-0 shadow-lg" style="background-color: rgba(0,0,0,0.15);">
    <label for="phone"><b>по телефону</b></label>
    @if (isset($_REQUEST['filter']['phone']) && ($_REQUEST['filter']['phone'] !== ''))
        <input type="text" class="form-control" id="phone" name="filter[phone]" value="{{$_REQUEST['filter']['phone']}}" style="background-color: rgba(255,255,255,0.3);">
    @else
        <input type="text" class="form-control" id="phone" name="filter[phone]" style="background-color: rgba(255,255,255,0.3);">
    @endif
</div>
