<div class="form-group border g-0 shadow-lg" style="background-color: rgba(0,0,0,0.15);">
    <label for="goods"><b>по товару</b></label>
    <select class="form-control" name="filter[goods]" id="goods" style="background-color: rgba(255,255,255,0.3);">
        <option selected="selected" value="">-</option>
        @foreach ($goods as $item)
            @if (
                isset($_REQUEST['filter']['goods']) &&
                ($_REQUEST['filter']['goods'] !== '') &&
                ($item['id'] == $_REQUEST['filter']['goods'])
                )
                <option selected="selected" value={{$item['id']}}>{{$item['name']}}</option>
            @else
                <option value={{$item['id']}}>{{$item['name']}}</option>
            @endif
        @endforeach
    </select>
</div>
