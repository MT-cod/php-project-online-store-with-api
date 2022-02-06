<div class="col text-left" style="height: 91vh !important; overflow-y: auto;">
    <div class="row p-0 m-0">
        <div class="col-10 d-flex justify-content-center pagination pagination-sm">
            {{ $orders->links('pagination::bootstrap-4') }}
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
                        Создан ▲
                    </th>
                @elseif (isset($_REQUEST['sort']['created_at']) && ($_REQUEST['sort']['created_at'] === 'desc'))
                    <th scope="col"
                        class="text-center clickableRow"
                        onclick="$('#sortByCreated').val('');$('#fsp').submit();"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Создан ▼
                    </th>
                @else
                    <th scope="col"
                        class="text-center clickableRow AddSortSimbol"
                        onclick="$('#sortByCreated').val('asc');$('#fsp').submit();"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Создан
                    </th>
                @endif
                @if (isset($_REQUEST['sort']['name']) && ($_REQUEST['sort']['name'] === 'asc'))
                    <th
                        scope="col"
                        class="text-center clickableRow"
                        onclick="$('#sortByName').val('desc');$('#fsp').submit();"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Имя заказчика ▲
                    </th>
                @elseif (isset($_REQUEST['sort']['name']) && ($_REQUEST['sort']['name'] === 'desc'))
                    <th scope="col"
                        class="text-center clickableRow"
                        onclick="$('#sortByName').val('');$('#fsp').submit();"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Имя заказчика ▼
                    </th>
                @else
                    <th scope="col"
                        class="text-center clickableRow AddSortSimbol"
                        onclick="$('#sortByName').val('asc');$('#fsp').submit();"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Имя заказчика
                    </th>
                @endif
                <th scope="col" class="text-center">E-mail</th>
                <th scope="col" class="text-center">Телефон</th>
                <th scope="col" class="text-center">Статус</th>
                {{--<th scope="col" class="text-center" style="width: 0">Действия</th>--}}
            </tr>
        </thead>
        <tbody style="background-color: rgba(0,0,0,0.05);">
            @foreach ($orders as $order)
                <tr class="text-center clickableRow btn-modal_order_edit" data-id="{{$order['id']}}">
                    <td class="text-break"><b>{{Str::limit($order['id'], 40)}}</b></td>
                    <td class="text-break">{{Str::limit($order['created_at'], 60)}}</td>
                    <td class="text-break">{{Str::limit($order['name'], 60)}}</td>
                    <td class="text-break">{{Str::limit($order['email'], 60)}}</td>
                    <td class="text-break">{{Str::limit($order['phone'], 60)}}</td>
                    <td class="text-break">
                        @if ($order['completed'])
                            <b>Завершён</b>
                        @else
                            <b>В работе</b>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
