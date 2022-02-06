<div class="col text-left" style="height: 91vh !important; overflow-y: auto;">
    <div class="row p-0 m-0">
        <div class="col-10 d-flex justify-content-center pagination pagination-sm">
            {{ $goods->links('pagination::bootstrap-4') }}
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
                @if (isset($_REQUEST['sort']['name']) && ($_REQUEST['sort']['name'] === 'asc'))
                    <th
                        scope="col"
                        class="text-center clickableRow"
                        onclick="$('#sortByName').val('desc');$('#fsp').submit();"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Наименование товара ▲
                    </th>
                @elseif (isset($_REQUEST['sort']['name']) && ($_REQUEST['sort']['name'] === 'desc'))
                    <th scope="col"
                        class="text-center clickableRow"
                        onclick="$('#sortByName').val('');$('#fsp').submit();"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Наименование товара ▼
                    </th>
                @else
                    <th scope="col"
                        class="text-center clickableRow AddSortSimbol"
                        onclick="$('#sortByName').val('asc');$('#fsp').submit();"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Наименование товара
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
                @if (isset($_REQUEST['sort']['updated_at']) && ($_REQUEST['sort']['updated_at'] === 'asc'))
                    <th
                        scope="col"
                        class="text-center clickableRow"
                        onclick="$('#sortByUpdated').val('desc');$('#fsp').submit();"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Изменён ▲
                    </th>
                @elseif (isset($_REQUEST['sort']['updated_at']) && ($_REQUEST['sort']['updated_at'] === 'desc'))
                    <th scope="col"
                        class="text-center clickableRow"
                        onclick="$('#sortByUpdated').val('');$('#fsp').submit();"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Изменён ▼
                    </th>
                @else
                    <th scope="col"
                        class="text-center clickableRow AddSortSimbol"
                        onclick="$('#sortByUpdated').val('asc');$('#fsp').submit();"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Изменён
                    </th>
                @endif
                <th scope="col" class="text-center">Описание товара</th>
                @if (isset($_REQUEST['sort']['price']) && ($_REQUEST['sort']['price'] === 'asc'))
                    <th
                        scope="col"
                        class="clickableRow"
                        onclick="$('#sortByPrice').val('desc');$('#fsp').submit();"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Цена ▲
                    </th>
                @elseif (isset($_REQUEST['sort']['price']) && ($_REQUEST['sort']['price'] === 'desc'))
                    <th scope="col"
                        class="clickableRow"
                        onclick="$('#sortByPrice').val('');$('#fsp').submit();"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Цена ▼
                    </th>
                @else
                    <th scope="col"
                        class="clickableRow AddSortSimbol"
                        onclick="$('#sortByPrice').val('asc');$('#fsp').submit();"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Цена
                    </th>
                @endif
            </tr>
        </thead>
        <tbody style="background-color: rgba(0,0,0,0.05);">
            @foreach ($goods as $item)
                <tr
                    class="text-left clickableRow btn-modal_goods_show"
                    data-id="{{$item['id']}}"
                    data-edit_route="{{route('goods.update', $item['id'])}}"
                    data-delete_route="{{route('goods.destroy', $item['id'])}}"
                    data-toggle="tooltip"
                    data-placement="bottom"
                    title="Нажать для подробностей/изменения"
                >
                    <td><h6><b>{{Str::limit($item['name'], 40)}}</b></h6></td>
                    <td class="text-center">{{Str::limit($item['created_at'], 40)}}</td>
                    <td class="text-center">{{Str::limit($item['updated_at'], 40)}}</td>
                    <td>{{Str::limit($item['description'], 120)}}</td>
                    <td><b>{{$item['price']}}</b></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
