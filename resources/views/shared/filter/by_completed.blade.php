<div class="form-group border g-0 shadow-lg" style="background-color: rgba(0,0,0,0.15);">
    <label for="completed"><b>по статусу</b></label>
    <select class="form-control" name="filter[completed]" id="completed" style="background-color: rgba(255,255,255,0.3);">
        @if (isset($_REQUEST['filter']['completed']) && ($_REQUEST['filter']['completed'] == 1))
            <option value="">-</option>
            <option selected="selected" value="1">Завершён</option>
            <option value="0">В работе</option>
        @elseif (isset($_REQUEST['filter']['completed']) && ($_REQUEST['filter']['completed'] == 0))
            <option value="">-</option>
            <option value="1">Завершён</option>
            <option selected="selected" value="0">В работе</option>
        @else
            <option selected="selected" value="">-</option>
            <option value="1">Завершён</option>
            <option value="0">В работе</option>
        @endif
    </select>
</div>
