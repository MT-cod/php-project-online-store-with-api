//Подключаем токен для запросов ajax глобально
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }});

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
            $('#modalItem-show').modal('show');
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
            $('#modalItem-edit-form').attr('action', route);
            $('#modalItem-show').modal('hide');
            $('.modal_goods_edit_save_results').html('');
            $('#modalItem-edit').modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown) {
        }});
});

//Попытка сохранения изменений
$(document).ready(function() {
    $("#modalItem-edit-form").submit(function(event) {
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
            $('#modalItem-create').modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown) {
        }});
});

//Попытка сохранения нового товара
$(document).ready(function() {
    $("#modalItem-create-form").submit(function(event) {
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
    console.log('pill ' + id + '   childCount =' + childCount);
    if ($(this).text() === childCount + "▲") {
        $('#categ-collapse-pill-' + id).html(childCount + "▼");
    } else {
        $('#categ-collapse-pill-' + id).html(childCount + "▲");
    }
});

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
