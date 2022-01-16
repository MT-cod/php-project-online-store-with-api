<div class="form-group border g-0 shadow-lg" style="background-color: rgba(0,0,0,0.15);">
    <label for="name"><b>по имени</b></label>
    @if (isset($_REQUEST['filter']['name']) && ($_REQUEST['filter']['name'] !== ''))
        <input type="text" class="form-control" id="name" name="filter[name]" value="{{$_REQUEST['filter']['name']}}" style="background-color: rgba(255,255,255,0.3);">
    @else
        <input type="text" class="form-control" id="name" name="filter[name]" style="background-color: rgba(255,255,255,0.3);">
    @endif
</div>
