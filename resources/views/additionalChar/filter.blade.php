@if (isset($_REQUEST['filter_expand']))
    <div class="col-2 collapse filt show" id="filter" style="height: 91vh !important; overflow-y: auto;">
@else
    <div class="col-2 collapse filt" id="filter" style="height: 91vh !important; overflow-y: auto;">
@endif
        <form class="text-center" id="fsp" method="GET" action="/additionalChars" accept-charset="UTF-8">
            <input type="hidden" name="filter_expand" value="1">

            <input id="perpage" type="hidden" name="perpage" value="{{ $_REQUEST['perpage'] ?? 20}}">

            <input id="sortByCreated" type="hidden" name="sort[created_at]" value="{{ $_REQUEST['sort']['created_at'] ?? ''}}">
            <input id="sortByUpdated" type="hidden" name="sort[updated_at]" value="{{ $_REQUEST['sort']['updated_at'] ?? ''}}">
            <input id="sortByName" type="hidden" name="sort[name]" value="{{ $_REQUEST['sort']['name'] ?? ''}}">
            <input id="sortByValue" type="hidden" name="sort[value]" value="{{ $_REQUEST['sort']['value'] ?? ''}}">

            @include('shared.filter.by_name')

            @include('shared.filter.by_value')

            @include('shared.filter.by_created_time')

            @include('shared.filter.by_updated_time')

            <div class="btn-block g-0">
                <a href="/additionalChars/?filter_expand=1" style="text-decoration: none">
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
