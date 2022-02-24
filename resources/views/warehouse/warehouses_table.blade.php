<div class="col text-left" style="height: 91vh !important; overflow-y: auto;">
    <table class="table table-bordered table-hover table-sm mx-auto">
        <thead style="background-color: rgba(255,255,255,0.1);">
            <tr style="font-size: 1.2rem;">
                <th scope="col" class="text-center">Наименование склада</th>
                <th scope="col" class="text-center">Описание</th>
                <th scope="col" class="text-center">Адрес</th>
                <th scope="col" class="text-center" style="width: 0">Приоритет</th>
                <th scope="col" class="text-center" style="width: 0">Создан</th>
                <th scope="col" class="text-center" style="width: 0">Изменения</th>
                <th scope="col" class="text-center" style="width: 0">Действия</th>
            </tr>
        </thead>
        <tbody style="background-color: rgba(255,255,255,0.3);">
            @foreach ($warehouses as $warehouse)
                <tr class="text-center">
                    <td><b>{{$warehouse['name']}}</b></td>
                    <td>{{$warehouse['description']}}</td>
                    <td><b>{{$warehouse['address']}}</b></td>
                    <td class=""><b>{{$warehouse['priority']}}</b></td>
                    <td class="text-center">{{$warehouse->created_at->format('d.m.Y H:i:s')}}</td>
                    <td class="text-center">{{$warehouse->updated_at->format('d.m.Y H:i:s')}}</td>
                    <td class="text-center btn-group">
                        @guest
                            <button type="button" class="btn btn-outline-warning" onclick="return alert('Для изменения склада необходимо авторизоваться!')">Изменить</button>
                            <button type="button" class="btn btn-outline-danger" onclick="return alert('Для удаления склада необходимо авторизоваться!')">Удалить</button>
                        @else
                            <button type="button" class="btn btn-outline-warning btn-sm btn-modal_warehouse_edit" data-id="{{$warehouse['id']}}">Изменить</button>
                            <form method="POST" action="{{route('warehouses.destroy', $warehouse['id'])}}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Вы действительно хотите удалить склад?')">Удалить</button>
                            </form>
                        @endguest
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
