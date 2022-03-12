<div class="form-group border g-0 shadow-lg" style="background-color: rgba(0,0,0,0.15);">
    <label for="movement_type"><b>по типу движения</b></label>
    <select class="form-control" name="filter[movement_type]" id="movement_type" style="background-color: rgba(255,255,255,0.3);">
        <option selected="selected" value="">-</option>
        @foreach (config('movements_types') as $type_key => $type_name)
            @if (
                isset($_REQUEST['filter']['movement_type']) &&
                ($_REQUEST['filter']['movement_type'] !== '') &&
                ($type_key == $_REQUEST['filter']['movement_type'])
                )
                <option selected="selected" value={{$type_key}}>{{$type_name}}</option>
            @else
                <option value={{$type_key}}>{{$type_name}}</option>
            @endif
        @endforeach
    </select>
</div>
