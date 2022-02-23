<div class="modal fade" id="modal-warehouse-create" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="modal-warehouse-create-form" method="POST" action="{{route('warehouses.store')}}">
                @csrf
                <div class="modal-header shadow" style="background-color: #dbfeff">
                    <h6><b>Создание нового склада</b></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="background-color: #dbfeff">
                    <span class="modal_warehouse_create_results"></span>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item" style="background-color: #e6f9ff">
                            <h6><b><label for="modal_warehouse_create_name">Имя склада</label></b></h6>
                            <input class="form-control" id="modal_warehouse_create_name" type="text" name="name" required>
                        </li>
                        <li class="list-group-item" style="background-color: #e6f9ff">
                            <h6><b><label for="modal_warehouse_create_description">Описание</label></b></h6>
                            <textarea class="form-control" id="modal_warehouse_create_description" rows="2" name="description"></textarea>
                        </li>
                        <li class="list-group-item" style="background-color: #e6f9ff">
                            <h6><b><label for="modal_warehouse_create_address">Адрес</label></b></h6>
                            <textarea class="form-control" id="modal_warehouse_create_address" rows="2" name="address"></textarea>
                        </li>
                        <li class="list-group-item" style="background-color: #e6f9ff">
                            <h6><b><label for="modal_warehouse_create_priority">Приоритет (от 1 до 100, меньшее значение приоритетнее при выборе склада для отгрузок/поступлений)</label></b></h6>
                            <input class="form-control" id="modal_warehouse_create_priority" type="number" name="priority" value="50" min="1" max="100" required>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer shadow" style="background-color: #dbfeff">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-success">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>
