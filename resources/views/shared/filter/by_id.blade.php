<div class="form-group border g-0 shadow-lg" style="background-color: rgba(0,0,0,0.15);">
    <label for="id"><b>по номеру заказа</b></label>
    @if (isset($_REQUEST['filter']['id']) && ($_REQUEST['filter']['id'] !== ''))
        <input type="text" class="form-control" id="id" name="filter[id]" value="{{$_REQUEST['filter']['id']}}" style="background-color: rgba(255,255,255,0.3);">
    @else
        <input type="text" class="form-control" id="id" name="filter[id]" style="background-color: rgba(255,255,255,0.3);">
    @endif
</div>
