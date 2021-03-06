//Модалки по доп характеристикам-----------------------------------------------------------------------

//Создание новой доп характеристики
//Попытка сохранения новой доп характеристики
$(document).ready(function () {
    $("#modal-additChar-create-form").submit(function (event) {
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
                inModalErrorFlashing(data.responseJSON.errors, ".modal_additChar_create_results")
            }
        });
    })
});
//Создание доп характеристики - end

//Изменение доп характеристики
$(document).on("click", ".btn-modal_additChar_edit", function () {
    let id = $(this).data('id');
    $.ajax({
        url: '/additionalChars/' + id + '/edit',
        method: "get",
        success: function (data, textStatus, jqXHR) {
            $('.modal_additChar_edit_title').html('<b>Редактирование ' + data.additChar.name + '</b>');
            $('#modal_additChar_edit_name').val(data.additChar.name);
            $('#modal_additChar_edit_value').val(data.additChar.value);
            $('.modal_additChar_edit_created_at').html(data.additChar.created_at);
            $('.modal_additChar_edit_updated_at').html(data.additChar.updated_at);

            $('.btn-modal_additChar_edit_save').html('<button type="submit" class="btn btn-outline-primary btn-modal_additChar_edit_save">Сохранить изменения</button>');
            $('#modal-additChar-edit-form').attr('action', '/additionalChars/' + id);
            $('.modal_additChar_edit_save_results').html('');
            $('#modal-additChar-edit').modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown) {
        }});
});

//Попытка сохранения изменений доп характеристики
$(document).ready(function () {
    $("#modal-additChar-edit-form").submit(function (event) {
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
                inModalErrorFlashing(data.responseJSON.errors, ".modal_additChar_edit_save_results")
            }
        });
    })
});
//Изменение доп характеристики - end
