//Подключаем токен для запросов ajax глобально
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }});

//Модалки по магазину-----------------------------------------------------------------------
//Подробности товара
$(document).on("click", ".btn-modal_shop_goods_show", function() {
    let id = $(this).data('id');
    let name = $(this).data('name');
    $.ajax({
        url: '/goods/' + id,
        method: "get",
        success: function(data, textStatus, jqXHR) {
            $('.modal_goods_show_title').html('<b>' + data.name + '</b>');
            $('.modal_goods_show_name').html(data.name);
            $('.modal_goods_show_description').html(data.description);
            $('.modal_goods_show_price').html(data.price);
            $('.modal_goods_show_category').html(data.category);
            $('.modal_goods_show_created_at').html(data.created_at);
            $('.modal_goods_show_updated_at').html(data.updated_at);
            $('.modal_goods_show_additional_chars').html(`${data.additional_chars.map((e) => {return `<b>${e.name}</b> (${e.value})<br/>`;}).join``}`);
            $('#goods_id').val(id);
            $('#goods_name').val(name);
            $('#modal-item-show').modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Ошибка получения данных о товаре<br>(' + textStatus + ')');
        }});
});
//Подробности товара - end

//Добавление товара в корзину
/*$(document).ready(function() {
    $("#modal-basket-form").submit(function(event) {
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
            success: function(data) {
                $('.modal_additChar_edit_save_results').html(
                    '<div class="alert alert-warning text-center" role="alert">' + data.success +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span></button></div>');
            },
            error: function(data) {
                let errors = '';
                console.log(data);
                Object.entries(data.responseJSON.errors).forEach(function(errNote) {
                    errors += errNote[1][0] + '<br>';
                });
                $('.modal_additChar_edit_save_results').html(
                    '<div class="alert alert-danger text-center" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span></button>' + errors + '</div>');
            }
        });
    })
});*/
//Добавление товара в корзину - end

//Изменение корзины или оформление заказа
$(document).ready(function() {
    $("#modal-basket-form").submit(function(event) {
        event.preventDefault();
        let data = new FormData(this);
        if (data.update) {console.log('true');}
        console.log(data.update);

        /*$.ajax({
            method: 'POST',
            url: this.action,
            cache: false,
            data: data,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(data) {
                $('.modal_additChar_edit_save_results').html(
                    '<div class="alert alert-warning text-center" role="alert">' + data.success +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span></button></div>');
            },
            error: function(data) {
                let errors = '';
                console.log(data);
                Object.entries(data.responseJSON.errors).forEach(function(errNote) {
                    errors += errNote[1][0] + '<br>';
                });
                $('.modal_additChar_edit_save_results').html(
                    '<div class="alert alert-danger text-center" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span></button>' + errors + '</div>');
            }
        });*/
    })
});
//Изменение корзины или оформление заказа - end

//Открытие корзины
$(document).on("click", ".btn-modal_basket_show", function() {
    $.ajax({
        url: '/basket',
        method: "get",
        dataType: 'json',
        success: function(data, textStatus, jqXHR) {
            let basket_rows = '';
            for(index_of_item in data.basket){
                basket_rows += `<b>${data.basket[index_of_item].name}</b> (${data.basket[index_of_item].quantity})<br/>`;
            }
            $('.modal_basket_show_goods').html(basket_rows);
            $('#modal-basket-show').modal('show');
            //alert(textStatus);
        },
        error: function (jqXHR, textStatus, errorThrown) {
        }});
});
//Открытие корзины - end


//Модалки по товарам-----------------------------------------------------------------------
//Просмотр товара
$(document).on("click", ".btn-modal_goods_show", function() {
    let id = $(this).data('id');
    let edit_route = $(this).data('edit_route');
    let delete_route = $(this).data('delete_route');
    $.ajax({
        url: '/goods/' + id,
        method: "get",
        success: function(data, textStatus, jqXHR) {
            $('.modal_goods_show_title').html('<b>' + data.name + '</b>');
            $('.modal_goods_show_name').html(data.name);
            $('.modal_goods_show_slug').html(data.slug);
            $('.modal_goods_show_description').html(data.description);
            $('.modal_goods_show_price').html(data.price);
            $('.modal_goods_show_category').html(data.category);
            $('.modal_goods_show_created_at').html(data.created_at);
            $('.modal_goods_show_updated_at').html(data.updated_at);
            $('.modal_goods_show_additional_chars').html(`${data.additional_chars.map((e) => {return `<b>${e.name}</b> (${e.value})<br/>`;}).join``}`);
            $('.modal_goods_edit_button').html('<button type="button" class="btn btn-warning btn-modal_goods_edit" data-id="' + id + '" data-route="' + edit_route + '" style="border: none">Изменить</button>');
            $('.modal_goods_delete_form').attr("action", delete_route);
            $('#modal-item-show').modal('show');
            //alert(textStatus);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Ошибка получения данных о товаре<br>(' + textStatus + ')');
        }});
});
//Просмотр товара - end

//Изменение товара
$(document).on("click", ".btn-modal_goods_edit", function() {
    let id = $(this).data('id');
    let route = $(this).data('route');
    $.ajax({
        url: '/goods/' + id + "/edit",
        method: "get",
        success: function(data, textStatus, jqXHR) {
            $('.modal_goods_edit_title').html('<b>' + data.item.name + '</b>');
            $('.modal_goods_edit_name').val(data.item.name);
            $('.modal_goods_edit_slug').val(data.item.slug);
            $('.modal_goods_edit_description').val(data.item.description);
            $('.modal_goods_edit_price').val(data.item.price);
            $('.modal_goods_edit_created_at').html(data.item.created_at);
            $('.modal_goods_edit_updated_at').html(data.item.updated_at);
            $('.modal_goods_edit_category').html(
                `<select class="form-control" name="category_id">` +
                `${data.categories.map((cat) => {
                    if (Number(cat.id) === Number(data.item.category_id)) {
                        return `<option selected="selected" value=${cat.id}>${cat.name}</option>`;
                    } else {
                        return `<option value=${cat.id}>${cat.name}</option>`;
                    }
                }).join``}` +
                `</select>`
            );
            $('.modal_goods_edit_additional_chars').html(
                `<div class="form-control" style="min-height: 20vh !important; max-height: 30vh !important; overflow-y: scroll !important;">` +
                `${data.additCharacteristics.map((char) => {
                    let res = `<div class="form-check">`;
                    if (idsInArray(char.id, data.item.additional_chars)) {
                        res += `<input name="additChars[]" type="checkbox" class="form-check-input" id="additChar_${char.id}" value=${char.id} checked>`;
                    } else {
                        res += `<input name="additChars[]" type="checkbox" class="form-check-input" id="additChar_${char.id}" value=${char.id}>`;
                    }
                    res += `<label class="form-check-label" for="additChar_${char.id}"><b>${char.name}</b> (${char.value})</label></div>`;
                    return res;
                }).join``}` +
                `</div>`
            );
            $('.btn-modal_goods_edit_save').html('<button type="submit" class="btn btn-primary btn-modal_goods_edit_save">Сохранить изменения</button>');
            $('#modal-item-edit-form').attr('action', route);
            $('#modal-item-show').modal('hide');
            $('.modal_goods_edit_save_results').html('');
            $('#modal-item-edit').modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown) {
        }});
});

//Попытка сохранения изменений товара
$(document).ready(function() {
    $("#modal-item-edit-form").submit(function(event) {
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
                $('.modal_goods_edit_save_results').html(
                    '<div class="alert alert-warning text-center" role="alert">' + data.success +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span></button></div>');
            },
            error: function(data) {
                let errors = '';
                Object.entries(data.responseJSON.errors).forEach(function(errNote) {
                    errors += errNote[1][0] + '<br>';
                });
                $('.modal_goods_edit_save_results').html(
                    '<div class="alert alert-danger text-center" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span></button>' + errors + '</div>');
            }
        });
    })
});
//Изменение товара - end

//Создание товара
$(document).on("click", ".btn-modal_goods_create", function() {
    $.ajax({
        url: '/goods/create',
        method: "get",
        success: function(data, textStatus, jqXHR) {
            $('.modal_goods_create_title').html('<b>Создание нового товара</b>');
            $('.modal_goods_create_category').html(
                `<select class="form-control" name="category_id">` +
                `${data.categories.map((cat) => {
                    return `<option value=${cat.id}>${cat.name}</option>`;
                }).join``}` +
                `</select>`
            );
            $('.modal_goods_create_additional_chars').html(
                `<div class="form-control" style="min-height: 20vh !important; max-height: 30vh !important; overflow-y: scroll !important;">` +
                `${data.additCharacteristics.map((char) => {
                    let res = `<div class="form-check">`;
                    res += `<input name="additChars[]" type="checkbox" class="form-check-input" id="additChar_${char.id}" value=${char.id}>`;
                    res += `<label class="form-check-label" for="additChar_${char.id}"><b>${char.name}</b> (${char.value})</label></div>`;
                    return res;
                }).join``}` +
                `</div>`
            );
            $('.modal_goods_create_results').html('');
            $('#modal-item-create').modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown) {
        }});
});

//Попытка сохранения нового товара
$(document).ready(function() {
    $("#modal-item-create-form").submit(function(event) {
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
                $('.modal_goods_create_results').html(
                    '<div class="alert alert-warning text-center" role="alert">' + data.success +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span></button></div>');
            },
            error: function(data) {
                let errors = '';
                Object.entries(data.responseJSON.errors).forEach(function(errNote) {
                    errors += errNote[1][0] + '<br>';
                });
                $('.modal_goods_create_results').html(
                    '<div class="alert alert-danger text-center" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span></button>' + errors + '</div>');
            }
        });
    })
});
//Создание товара - end


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
                Object.entries(data.responseJSON.errors).forEach(function(errNote) {
                    errors += errNote[1][0] + '<br>';
                });
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
                Object.entries(data.responseJSON.errors).forEach(function(errNote) {
                    errors += errNote[1][0] + '<br>';
                });
                $('.modal_categ_edit_save_results').html(
                    '<div class="alert alert-danger text-center" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span></button>' + errors + '</div>');
            }
        });
    })
});
//Изменение категории - end


//Модалки по доп характеристикам-----------------------------------------------------------------------

//Создание новой доп характеристики
//Попытка сохранения новой доп характеристики
$(document).ready(function() {
    $("#modal-additChar-create-form").submit(function(event) {
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
            success: function(data) {
                $('.modal_additChar_create_results').html(
                    '<div class="alert alert-warning text-center" role="alert">' + data.success +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span></button></div>');
            },
            error: function(data) {
                let errors = '';
                Object.entries(data.responseJSON.errors).forEach(function(errNote) {
                    errors += errNote[1][0] + '<br>';
                });
                $('.modal_additChar_create_results').html(
                    '<div class="alert alert-danger text-center" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span></button>' + errors + '</div>');
            }
        });
    })
});
//Создание доп характеристики - end

//Изменение доп характеристики
$(document).on("click", ".btn-modal_additChar_edit", function() {
    let id = $(this).data('id');
    $.ajax({
        url: '/additionalChars/' + id + '/edit',
        method: "get",
        success: function(data, textStatus, jqXHR) {
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
$(document).ready(function() {
    $("#modal-additChar-edit-form").submit(function(event) {
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
            success: function(data) {
                $('.modal_additChar_edit_save_results').html(
                    '<div class="alert alert-warning text-center" role="alert">' + data.success +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span></button></div>');
            },
            error: function(data) {
                let errors = '';
                console.log(data);
                Object.entries(data.responseJSON.errors).forEach(function(errNote) {
                    errors += errNote[1][0] + '<br>';
                });
                $('.modal_additChar_edit_save_results').html(
                    '<div class="alert alert-danger text-center" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span></button>' + errors + '</div>');
            }
        });
    })
});
//Изменение доп характеристики - end

//-----------------------------------------------------------------------------------------------------------
//Доп функции
function idsInArray(needleId, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if (haystack[i]['id'] == needleId) return true;
    }
    return false;
}

//-----------------------------------------------------------------------------------------------------------
/*$(function(){
    var two_modal = function(id_modal_1,id_modal_2) {
        // определяет, необходимо ли при закрытии текущего модального окна открыть другое
        // var show_modal_2 = false;
        // при нажатии на ссылку, содержащей в качестве href селектор модального окна
        $(document).on("click", ".btn-modal_goods_edit", function(e) {
            e.preventDefault();
            show_modal_2 = true;
            // скрыть текущее модальное окно
            $(id_modal_1).modal('hide');
        });
        // при скрытии текущего модального окна открыть другое, если значение переменной show_modal_2 равно true
        $(id_modal_1).on('hidden.bs.modal', function (e) {
            if (show_modal_2) {
                show_modal_2 = false;
                $(id_modal_2).modal('show');
            }
        })
    }('#modalItem-show','#modalItem-edit');
});*/

/*    // при открытии модального окна
    $('#myModal').on('show.bs.modal', function (event) {
    // получить кнопку, которая его открыло
    var button = $(event.relatedTarget)
    // извлечь информацию из атрибута data-content
    var content = button.data('content')
    // вывести эту информацию в элемент, имеющий id="content"
    //$(this).find('#content').text(content);
    $(content).modal('hide');
})*/

/*if (window.jQuery) {
    var verJquery = jQuery.fn.jquery;
    // выведем версию jQuery в консоль
    console.log(verJquery);
}

    $(function(){
// #modal_1 - селектор 1 модального окна
// #modal_2 - селектор 2 модального окна, которое необходимо открыть из первого
    var two_modal = function(id_modal_1,id_modal_2) {
    // определяет, необходимо ли при закрытии текущего модального окна открыть другое
    var show_modal_2 = false;
    // при нажатии на ссылку, содержащей в качестве href селектор модального окна
    $('a[href="' + id_modal_2 + '"]').click(function(e) {
    e.preventDefault();
    show_modal_2 = true;
    // скрыть текущее модальное окно
    $(id_modal_1).modal('hide');
});
    // при скрытии текущего модального окна открыть другое, если значение переменной show_modal_2 равно true
    $(id_modal_1).on('hidden.bs.modal', function (e) {
    if (show_modal_2) {
    show_modal_2 = false;
    $(id_modal_2).modal('show');
}
})

}('#modalItem-241','#modalItem-241-edit');

});*/


//$("input[name='showName']").val(name);


/*$(document).on("click", ".btn-modal_goods_edit_save", function() {
    //var formData = new FormData(this);
    var id = $(this).data('id');
    var route = $(this).data('route');
    var name = $('.modal_goods_edit_name').val();
    var slug = $('.modal_goods_edit_slug').val();
    var description = $('.modal_goods_edit_description').val();
    var price = $('.modal_goods_edit_price').val();
    var category_id = $("select[name='modal_goods_edit_category']").val();
    //var additChars = $("input[type=checkbox]:checked");//$('.additChar').val();
    /!*$("input[type=checkbox]:checked").each(function(i){
        console.log("i.value :"+i.value);
    });*!/
    $.ajax({
        url: route,
        type: "POST",
        _method: "PATCH",
        data: {name:name, slug:slug, description:description, price:price, category_id:category_id},//, additChars:additChars
        success: function (data, textStatus, jqXHR) {
            //$('#modalItem-edit').modal('hide');
            alert('Саксус' + textStatus + data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Ошибка' + textStatus + errorThrown);
        }
    });
});*/
