<div class="modal fade" id="modal-warehouse-edit" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="modal-warehouse-edit-form" method="POST" action="/warehouses">
                @csrf
                @method('PATCH')
                <div class="modal-header shadow" style="background-color: #dbfeff">
                    <h4 id="modal_warehouse_edit_title"><b></b></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="background-color: #dbfeff">
                    <span id="modal_warehouse_edit_results"></span>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item" style="background-color: #e6f9ff">
                            <h6><b><label for="modal_warehouse_edit_name">Имя склада</label></b></h6>
                            <input class="form-control" id="modal_warehouse_edit_name" type="text" name="name" required>
                        </li>
                        <li class="list-group-item" style="background-color: #e6f9ff">
                            <h6><b><label for="modal_warehouse_edit_description">Описание</label></b></h6>
                            <textarea class="form-control" id="modal_warehouse_edit_description" rows="2" name="description"></textarea>
                        </li>
                        <li class="list-group-item" style="background-color: #e6f9ff">
                            <h6><b><label for="modal_warehouse_edit_address">Адрес</label></b></h6>
                            <textarea class="form-control" id="modal_warehouse_edit_address" rows="2" name="address"></textarea>
                        </li>
                        <li class="list-group-item" style="background-color: #e6f9ff">
                            <h6><b><label for="modal_warehouse_edit_priority">Приоритет (от 1 до 100, меньшее значение приоритетнее при выборе склада для отгрузок/поступлений)</label></b></h6>
                            <input class="form-control" id="modal_warehouse_edit_priority" type="number" name="priority" value="50" min="1" max="100" required>
                        </li>
                        <li class="list-group-item" style="background-color: #e6fff4">
                            <div class="row">
                                <div class="col">
                                    <h6><b>Время создания склада</b></h6>
                                    <p><span id="modal_warehouse_edit_created_at"></span></p>
                                </div>
                                <div class="col">
                                    <h6><b>Время последнего изменения склада</b></h6>
                                    <p><span id="modal_warehouse_edit_updated_at"></span></p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer shadow" style="background-color: #dbfeff">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    <div id="btn-modal_warehouse_edit_save"></div>
                </div>
            </form>
        </div>
    </div>
</div>
