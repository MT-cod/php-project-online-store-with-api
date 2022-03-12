@if (isset($_REQUEST['filter_expand']))
    <div class="col-2 collapse filt show" id="filter" style="height: 91vh !important; overflow-y: auto;">
@else
    <div class="col-2 collapse filt" id="filter" style="height: 91vh !important; overflow-y: auto;">
@endif
        <form class="text-center" id="fsp" method="GET" action="/movements" accept-charset="UTF-8">
            <input type="hidden" name="filter_expand" value="1">

            <input id="perpage" type="hidden" name="perpage" value="{{ $_REQUEST['perpage'] ?? 20}}">

            <input id="sortById" type="hidden" name="sort[id]" value="{{ $_REQUEST['sort']['id'] ?? 'asc'}}">
            <input id="sortByCreated" type="hidden" name="sort[created_at]" value="{{ $_REQUEST['sort']['created_at'] ?? ''}}">

            @include('shared.filter.by_created_time')
            @include('shared.filter.by_id')
            @include('shared.filter.by_movement_type')
            @include('shared.filter.by_goods')
            @include('shared.filter.by_warehouses')

            <div class="btn-block g-0">
                <a href="/movements/?filter_expand=1&sort[id]=desc" style="text-decoration: none">
                    <button class="btn btn-secondary collapse multi_filt show" type="button" id="submit_filt1" data-toggle="collapse" data-target=".multi_filt" aria-controls="submit_filt1 submit_filt2">
                        Сброс фильтра
                    </button>
                </a>
                <button id="submit_filt2" class="btn btn-secondary collapse multi_filt" type="button" data-toggle="collapse" data-target=".multi_filt" aria-controls="submit_filt1 submit_filt2">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Сброс фильтра
                </button>

                <input class="btn btn-secondary collapse multi-collapse show" id="submit1" type="submit" value="Применить" data-toggle="collapse" data-target=".multi-collapse" aria-controls="submit1 submit2">
                <button id="submit2" class="btn btn-secondary collapse multi-collapse" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-controls="submit1 submit2">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Применить
                </button>
            </div>
        </form>
    </div>
