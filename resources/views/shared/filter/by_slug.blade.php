<div class="form-group border g-0 shadow-lg" style="background-color: rgba(0,0,0,0.15);">
    <label for="slug"><b>по slug</b></label>
    @if (isset($_REQUEST['filter']['slug']) && ($_REQUEST['filter']['slug'] !== ''))
        <input type="text" class="form-control" id="slug" name="filter[slug]" value="{{$_REQUEST['filter']['slug']}}" style="background-color: rgba(255,255,255,0.3);">
    @else
        <input type="text" class="form-control" id="slug" name="filter[slug]" style="background-color: rgba(255,255,255,0.3);">
    @endif
</div>
