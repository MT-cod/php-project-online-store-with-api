<div class="form-group border g-0 shadow-lg" style="background-color: rgba(0,0,0,0.15);">
    <label for="category"><b>по категории</b></label>
    <select class="form-control" name="filter[category_id]" id="category" style="background-color: rgba(255,255,255,0.3);">
        <option selected="selected" value="">-</option>
        @foreach ($categories as $cat)
            @if (
                isset($_REQUEST['filter']['category_id']) &&
                ($_REQUEST['filter']['category_id'] !== '') &&
                ($cat['id'] == $_REQUEST['filter']['category_id'])
                )
                <option selected="selected" value={{$cat['id']}}>{{$cat['name']}}</option>
            @else
                <option value={{$cat['id']}}>{{$cat['name']}}</option>
            @endif
        @endforeach
    </select>
</div>
