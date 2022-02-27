<div class="col text-left" style="height: 91vh !important; overflow-y: auto;">
    <div class="row p-0 m-0">
        <div class="col-10 d-flex justify-content-center pagination pagination-sm">
            {{ $movements->links('pagination::bootstrap-4') }}
        </div>
        <div class="col-2 p-sm-1 m-0 d-flex justify-content-center">
            <b>Показать&nbsp;</b>
            <input href="#" onclick="$('#perpage').val(20)" type="submit" form="fsp" value="20">
            <input href="#" onclick="$('#perpage').val(50)" type="submit" form="fsp" value="50">
            <input href="#" onclick="$('#perpage').val(500)" type="submit" form="fsp" value="500">
        </div>
    </div>
    <table class="table table-bordered table-hover table-sm mx-auto">
        <thead style="background-color: rgba(0,0,0,0.1);">
            <tr style="font-size: 1.2rem;">
                @if (isset($_REQUEST['sort']['id']) && ($_REQUEST['sort']['id'] === 'asc'))
                    <th
                        scope="col"
                        class="text-center clickableRow"
                        onclick="$('#sortById').val('');$('#fsp').submit();"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        № ▲
                    </th>
                @elseif (isset($_REQUEST['sort']['id']) && ($_REQUEST['sort']['id'] === 'desc'))
                    <th scope="col"
                        class="text-center clickableRow"
                        onclick="$('#sortById').val('asc');$('#fsp').submit();"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        № ▼
                    </th>
                @else
                    <th scope="col"
                        class="text-center clickableRow AddSortSimbol"
                        onclick="$('#sortById').val('desc');$('#fsp').submit();"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        №
                    </th>
                @endif
                @if (isset($_REQUEST['sort']['created_at']) && ($_REQUEST['sort']['created_at'] === 'asc'))
                    <th
                        scope="col"
                        class="text-center clickableRow"
                        onclick="$('#sortByCreated').val('desc');$('#fsp').submit();"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Проведено ▲
                    </th>
                @elseif (isset($_REQUEST['sort']['created_at']) && ($_REQUEST['sort']['created_at'] === 'desc'))
                    <th scope="col"
                        class="text-center clickableRow"
                        onclick="$('#sortByCreated').val('');$('#fsp').submit();"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Проведено ▼
                    </th>
                @else
                    <th scope="col"
                        class="text-center clickableRow AddSortSimbol"
                        onclick="$('#sortByCreated').val('asc');$('#fsp').submit();"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Проведено
                    </th>
                @endif
                <th scope="col" class="text-center">Описание</th>
                <th scope="col" class="text-center">Тип движения</th>
            </tr>
        </thead>
        <tbody style="background-color: rgba(0,0,0,0.05);">
            @foreach ($movements as $movement)
                <tr class="text-center clickableRow btn-modal_order_edit" data-id="{{$movement['id']}}">
                    <td><b>{{Str::limit($movement['id'], 40)}}</b></td>
                    <td class="text-break">{{Str::limit($movement['created_at'], 60)}}</td>
                    <td class="text-break text-left">{{$movement['description']}}</td>
                    <td class="text-break">
                        @switch($movement['movement_type'])
                            @case(1)
                            <b>пополнение склада</b>
                            @break
                            @case(2)
                            <b>списание со склада</b>
                            @break
                            @case(3)
                            <b>выдача со склада по заказу</b>
                            @break
                            @case(4)
                            <b>движение между складами</b>
                            @break
                        @endswitch
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
