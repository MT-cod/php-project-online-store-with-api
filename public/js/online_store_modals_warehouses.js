//Модалки по складам-----------------------------------------------------------------------

//Создание нового склада
//Попытка сохранения нового склада
$(document).ready(function () {
    $("#modal-warehouse-create-form").submit(function (event) {
        event.preventDefault();
        let data = new FormData(this);
        $.ajax({
            method: 'POST',
            url: this.action,
            cache: false,
            data: data,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function (data) {
                location = data.referer;
            },
            error: function (data) {
                inModalErrorFlashing(data.responseJSON.errors, "#modal_warehouse_create_results")
            }
        });
    })
});
//Попытка сохранения нового склада - end

//Изменение нового склада
$(document).on("click", ".btn-modal_warehouse_edit", function () {
    let id = $(this).data('id');
    $.ajax({
        url: '/warehouses/' + id + '/edit',
        method: "get",
        success: function (data, textStatus, jqXHR) {
            $('#modal_warehouse_edit_title').html('<b>Редактирование ' + data.warehouse.name + '</b>');
            $('#modal_warehouse_edit_name').val(data.warehouse.name);
            $('#modal_warehouse_edit_description').val(data.warehouse.description);
            $('#modal_warehouse_edit_address').val(data.warehouse.address);
            $('#modal_warehouse_edit_priority').val(data.warehouse.priority);
            $('#modal_warehouse_edit_created_at').html(data.warehouse.created_at);
            $('#modal_warehouse_edit_updated_at').html(data.warehouse.updated_at);

            $('#btn-modal_warehouse_edit_save').html('<button type="submit" class="btn btn-outline-primary btn-modal_warehouse_edit_save">Сохранить изменения</button>');
            $('#modal-warehouse-edit-form').attr('action', '/warehouses/' + id);
            $('#modal_warehouse_edit_save_results').html('');
            $('#modal-warehouse-edit').modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown) {
        }});
});

//Попытка сохранения изменений нового склада
$(document).ready(function () {
    $("#modal-warehouse-edit-form").submit(function (event) {
        event.preventDefault();
        let data = new FormData(this);
        $.ajax({
            method: 'POST',
            url: this.action,
            cache: false,
            data: data,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function (data) {
                location = data.referer;
            },
            error: function (data) {
                inModalErrorFlashing(data.responseJSON.errors, "#modal_warehouse_edit_results")
            }
        });
    })
});
//Попытка сохранения изменений нового склада - end
