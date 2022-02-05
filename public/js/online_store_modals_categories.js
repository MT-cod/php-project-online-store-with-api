//Модалки по категориям-----------------------------------------------------------------------

//Переключение стрелки в иконке разворачивания/сворачивания категории
$(document).on("click", ".categ-collapse-pill", function() {
    let id = $(this).data('id');
    let childCount = $(this).data('childcount');
    if ($(this).text() === childCount + "▲") {
        $('#categ-collapse-pill-' + id).html(childCount + "▼");
    } else {
        $('#categ-collapse-pill-' + id).html(childCount + "▲");
    }
});

//Создание категории
$(document).on("click", ".btn-modal_category_create", function() {
    $.ajax({
        url: '/categories/create',
        method: "get",
        success: function(data, textStatus, jqXHR) {
            $('.modal_categ_create_title').html('<b>Создание новой категории</b>');
            $('.modal_create_categ_parent_category').html(
                `<select class="form-control" name="parent_id">` +
                `<option selected="selected" value="0">-</option>` +
                `${data.categories.map((cat) => {
                    return `<option value=${cat.id}>${cat.name}</option>`;
                }).join``}` +
                `</select>`
            );
            $('.modal_categ_create_results').html('');
            $('#modal-categ-create').modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown) {
        }});
});

//Попытка сохранения новой категории
$(document).ready(function() {
    $("#modal-categ-create-form").submit(function(event) {
        // Отменяем стандартное поведение формы на submit.
        event.preventDefault();
        // Собираем данные с формы. Здесь будут все поля у которых есть `name`, включая метод `_method` и `_token`.
        let data = new FormData(this);
        // Отправляем запрос.
        $.ajax({
            method: 'POST', // начиная с версии 1.9 `type` - псевдоним для `method`.
            url: this.action, // атрибут `action="..."` из формы.
            cache: false, // запрошенные страницы не будут закешированы браузером.
            data: data, // больше ничего тут не надо!
            dataType: 'json', // чтобы jQuery распарсил `success` ответ.
            processData: false, // чтобы jQuery не обрабатывал отправляемые данные.
            contentType: false, // чтобы jQuery не передавал в заголовке поле `Content-Type` совсем.
            success: function(data) {
                $('.modal_categ_create_results').html(
                    '<div class="alert alert-warning text-center" role="alert">' + data.success +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span></button></div>');
            },
            error: function(data) {
                let errors = '';
                let respErrors = data.responseJSON.errors;
                if (typeof respErrors == 'string') {
                    errors = respErrors;
                }
                if (typeof respErrors == 'object') {
                    Object.entries(data.responseJSON.errors).forEach(function (errNote) {
                        errors += errNote[1][0] + '<br>';
                    });
                }
                $('.modal_categ_create_results').html(
                    '<div class="alert alert-danger text-center" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span></button>' + errors + '</div>');
            }
        });
    })
});
//Создание категории - end

//Изменение категории
$(document).on("click", ".btn-modal_category_edit", function() {
    let id = $(this).data('id');
    let firstLvl = '';
    $.ajax({
        url: '/categories/' + id + '/edit',
        method: "get",
        success: function(data, textStatus, jqXHR) {
            $('.modal_categ_edit_title').html('<b>Редактирование ' + data.cat.name + '</b>');
            $('.modal_categ_edit_name').val(data.cat.name);
            $('.modal_categ_edit_description').val(data.cat.description);
            $('.modal_categ_edit_created_at').html(data.cat.created_at);
            $('.modal_categ_edit_updated_at').html(data.cat.updated_at);
            if (Number(data.cat.parent_id) === 0) {
                firstLvl = `<option selected="selected" value="0">-</option>`;
            } else {
                firstLvl = `<option value="0">-</option>`;
            }
            $('.modal_categ_edit_parent_category').html(
                `<select class="form-control" name="parent_id">` +
                firstLvl +
                `${data.categories.map((categs) => {
                    if (Number(categs.id) === Number(data.cat.parent_id)) {
                        return `<option selected="selected" value=${categs.id}>${categs.name}</option>`;
                    } else {
                        return `<option value=${categs.id}>${categs.name}</option>`;
                    }
                }).join``}` +
                `</select>`
            );

            $('.btn-modal_categ_edit_save').html('<button type="submit" class="btn btn-outline-primary btn-modal_goods_edit_save">Сохранить изменения</button>');
            $('#modal-categ-edit-form').attr('action', '/categories/' + id);
            $('.modal_categ_edit_save_results').html('');
            $('#modal-categ-edit').modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown) {
        }});
});

//Попытка сохранения изменений категории
$(document).ready(function() {
    $("#modal-categ-edit-form").submit(function(event) {
        // Отменяем стандартное поведение формы на submit.
        event.preventDefault();
        // Собираем данные с формы. Здесь будут все поля у которых есть `name`, включая метод `_method` и `_token`.
        let data = new FormData(this);
        // Отправляем запрос.
        $.ajax({
            method: 'POST', // начиная с версии 1.9 `type` - псевдоним для `method`.
            url: this.action, // атрибут `action="..."` из формы.
            cache: false, // запрошенные страницы не будут закешированы браузером.
            data: data, // больше ничего тут не надо!
            dataType: 'json', // чтобы jQuery распарсил `success` ответ.
            processData: false, // чтобы jQuery не обрабатывал отправляемые данные.
            contentType: false, // чтобы jQuery не передавал в заголовке поле `Content-Type` совсем.
            success: function(data) {
                $('.modal_categ_edit_save_results').html(
                    '<div class="alert alert-warning text-center" role="alert">' + data.success +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span></button></div>');
            },
            error: function(data) {
                let errors = '';
                let respErrors = data.responseJSON.errors;
                if (typeof respErrors == 'string') {
                    errors = respErrors;
                }
                if (typeof respErrors == 'object') {
                    Object.entries(data.responseJSON.errors).forEach(function (errNote) {
                        errors += errNote[1][0] + '<br>';
                    });
                }
                $('.modal_categ_edit_save_results').html(
                    '<div class="alert alert-danger text-center" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span></button>' + errors + '</div>');
            }
        });
    })
});
//Изменение категории - end
