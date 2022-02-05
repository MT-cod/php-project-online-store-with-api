<div class="col text-left" style="height: 91vh !important; overflow-y: auto;">
    <div class="row p-0 m-0">
        <div class="col-10 d-flex justify-content-center pagination pagination-sm">
            {{ $additChars->links('pagination::bootstrap-4') }}
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
                        class="text-center clickableRow sortingGoodsTable"
                        data-sort_col_name="name"
                        data-sort_col_name_text="Наименование"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Наименование ▲
                    </th>
                @elseif (isset($_REQUEST['sort']['name']) && ($_REQUEST['sort']['name'] === 'desc'))
                    <th scope="col"
                        class="text-center clickableRow sortingGoodsTable"
                        data-sort_col_name="name"
                        data-sort_col_name_text="Наименование"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Наименование ▼
                    </th>
                @else
                    <th scope="col"
                        class="text-center clickableRow sortingGoodsTable AddSortSimbol"
                        data-sort_col_name="name"
                        data-sort_col_name_text="Наименование"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Наименование
                    </th>
                @endif
                @if (isset($_REQUEST['sort']['value']) && ($_REQUEST['sort']['value'] === 'asc'))
                    <th
                        scope="col"
                        class="text-center clickableRow sortingGoodsTable"
                        data-sort_col_name="value"
                        data-sort_col_name_text="Значение"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Значение ▲
                    </th>
                @elseif (isset($_REQUEST['sort']['value']) && ($_REQUEST['sort']['value'] === 'desc'))
                    <th scope="col"
                        class="text-center clickableRow sortingGoodsTable"
                        data-sort_col_name="value"
                        data-sort_col_name_text="Значение"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Значение ▼
                    </th>
                @else
                    <th scope="col"
                        class="text-center clickableRow sortingGoodsTable AddSortSimbol"
                        data-sort_col_name="value"
                        data-sort_col_name_text="Значение"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Значение
                    </th>
                @endif
                @if (isset($_REQUEST['sort']['created_at']) && ($_REQUEST['sort']['created_at'] === 'asc'))
                    <th
                        scope="col"
                        class="text-center clickableRow sortingGoodsTable"
                        data-sort_col_name="created_at"
                        data-sort_col_name_text="Создана"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Создана ▲
                    </th>
                @elseif (isset($_REQUEST['sort']['created_at']) && ($_REQUEST['sort']['created_at'] === 'desc'))
                    <th scope="col"
                        class="text-center clickableRow sortingGoodsTable"
                        data-sort_col_name="created_at"
                        data-sort_col_name_text="Создана"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Создана ▼
                    </th>
                @else
                    <th scope="col"
                        class="text-center clickableRow sortingGoodsTable AddSortSimbol"
                        data-sort_col_name="created_at"
                        data-sort_col_name_text="Создана"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Создана
                    </th>
                @endif
                @if (isset($_REQUEST['sort']['updated_at']) && ($_REQUEST['sort']['updated_at'] === 'asc'))
                    <th
                        scope="col"
                        class="text-center clickableRow sortingGoodsTable"
                        data-sort_col_name="updated_at"
                        data-sort_col_name_text="Изменена"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Изменена ▲
                    </th>
                @elseif (isset($_REQUEST['sort']['updated_at']) && ($_REQUEST['sort']['updated_at'] === 'desc'))
                    <th scope="col"
                        class="text-center clickableRow sortingGoodsTable"
                        data-sort_col_name="updated_at"
                        data-sort_col_name_text="Изменена"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Изменена ▼
                    </th>
                @else
                    <th scope="col"
                        class="text-center clickableRow sortingGoodsTable AddSortSimbol"
                        data-sort_col_name="updated_at"
                        data-sort_col_name_text="Изменена"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Нажать для сортировки">
                        Изменена
                    </th>
                @endif
                <th scope="col" class="text-center">Действия</th>
            </tr>
        </thead>
        <tbody style="background-color: rgba(0,0,0,0.05);">
            @foreach ($additChars as $char)
                <tr class="text-left">
                    <td><b>{{$char['name']}}</b></td>
                    <td><b>{{$char['value']}}</b></td>
                    <td class="text-center">{{Str::limit($char['created_at'], 40)}}</td>
                    <td class="text-center">{{Str::limit($char['updated_at'], 40)}}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-outline-secondary btn-sm btn-modal_additChar_edit" data-id="{{$char['id']}}">Изменить</button>
                        @if (count($char['goods']))
                            @php($goodsNames = implode(array_map(static fn ($goodsName) => $goodsName['name'] . '\n', $char['goods']->toArray())))
                            <form method="POST" action="{{route('additionalChars.destroy', $char['id'])}}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('В базе имеются товары с данной характеристикой!\n{{$goodsNames}}Вы действительно хотите удалить характеристику?\nУ товаров будет убрана данная характеристика!')">Удалить</button>
                            </form>
                        @else
                            <form method="POST" action="{{route('additionalChars.destroy', $char['id'])}}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Вы действительно хотите удалить характеристику?')">Удалить</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
