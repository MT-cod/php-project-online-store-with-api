//Модалки по магазину-----------------------------------------------------------------------
//Подробности товара
$(document).on("click", ".btn-modal_shop_goods_show", function() {
    let id = $(this).data('id');
    $.ajax({
        url: '/goods/' + id,
        method: "get",
        success: function(data, textStatus, jqXHR) {
            $('.modal_goods_show_title').html('<b>' + data.name + '</b>');
            $('.modal_goods_show_name').html(data.name);
            $('.modal_goods_show_image').html(`<img src="${data.image}" className="img-fluid" alt="">`);
            $('.modal_goods_show_description').html(data.description);
            $('.modal_goods_show_price').html(data.price);
            $('.modal_goods_show_category').html(data.category);
            $('.modal_goods_show_additional_chars').html(`${data.additional_chars.map((e) => {return `<b>${e.name}</b> (${e.value})<br/>`;}).join``}`);
            $('#goods_id').val(id);
            $('#modal-item-show').modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Ошибка получения данных о товаре<br>(' + textStatus + ')');
        }});
});
//Подробности товара - end

//Изменение корзины
$(document).ready(function() {
    $(".modal-basket-form").submit(function(event) {
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
                $('.modal_basket_edit_results').html('');
                $('.modal_basket_edit_results').html(
                    '<div class="alert alert-success text-center p-0 m-0" role="alert">' + data.success +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span></button></div>');
                showItemsOfBasket(data);
            },
            error: function(data) {
                $('.modal_basket_edit_results').html('');
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
                $('.modal_basket_edit_results').html(
                    '<div class="alert alert-danger text-center" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span></button>' + errors + '</div>');
                showItemsOfBasket(data.responseJSON);
            }
        });
    })
});
//Изменение корзины - end

//Открытие корзины
$(document).on("click", ".btn-modal_basket_show", function() {
    $.ajax({
        url: '/basket',
        method: "get",
        dataType: 'json',
        success: function(data) {
            $('.modal_basket_edit_results').html('');
            showItemsOfBasket(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
        }
    });
});
//Открытие корзины - end

//Удаление позиции из корзины
$(document).on("click", ".btn-del-item", function() {
    let id = $(this).data('id');
    var fd = new FormData();
    fd.append("_method", "DELETE");
    fd.append("_token", csrf_token);
    $.ajax({
        method: 'POST',
        url: '/basket/' + id,
        cache: false,
        data: fd,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function(data) {
            $('.modal_basket_edit_results').html('');
            $('.modal_basket_edit_results').html(
                '<div class="alert alert-success text-center p-0 m-0" role="alert">' + data.success +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span></button></div>');
            showItemsOfBasket(data);
            showItemsOfBasket(data);
        },
        error: function(data) {
        }
    });
});
//Удаление позиции из корзины - end

//Окно оформления заказа
$(document).on("click", "#btn-basket-preorder", function() {
    $.ajax({
        url: '/orders/create',
        method: "get",
        dataType: 'json',
        success: function(data) {
            let basketLen = Object.keys(data.basket).length;
            if (basketLen !== 0) {
                basketItems = '';
                let basketCost = 0;
                basketItems = `<table class="table table-bordered table-sm table-hover" style="background-color: rgba(0,0,0,0.05)">
                        <thead><tr class="text-center" style="background-color: rgba(0,0,0,0.08)">
                            <th scope="col">Имя товара</th>
                            <th scope="col">Цена</th>
                            <th scope="col">Кол-во</th>
                            <th scope="col">Сумма</th>
                        </tr></thead><tbody>`;
                for (i in data.basket) {
                    let itemCost = Math.round((data.basket[i].price * data.basket[i].quantity) * 100) / 100;
                    basketCost += itemCost;
                    basketItems += `<tr>
                                 <td class="text-center text-break"><b>${data.basket[i].name}</b></td>
                                 <td class="text-center">${data.basket[i].price}</td>
                                 <td class="text-center">${data.basket[i].quantity}</td>
                                 <td class="text-center">${itemCost}</td>
                             </tr>`;
                }
                basketItems += `</tbody></table>`;
                basketItems += `<div class="col text-right"><b>Итого: ${Math.round(basketCost * 100) / 100}</b></div>`;

                $('#modal_preorder_name').val(data.user_data.name);
                $('#modal_preorder_email').val(data.user_data.email);
                $('#modal_preorder_phone').val(data.user_data.phone);
                $('.modal_preorder_basket').html(basketItems);
                $('#modal-basket-show').modal('hide');
                $('#modal-preorder-show').modal('show');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
        }});
});
//Окно оформления заказа - end

//Доп функции
function showItemsOfBasket(data) {
    let basketLen = Object.keys(data.basket).length;
    $('.baskCount').html(basketLen);
    let basketItems = `<b><div class="text-center">Нет выбранных товаров</div></b>`;
    if (basketLen !== 0) {
        basketItems = '';
        let basketCost = 0;
        basketItems = `<table class="table table-bordered table-hover table-sm" style="background-color: rgba(0,0,0,0.05)">
                        <thead><tr class="text-center" style="background-color: rgba(0,0,0,0.08)">
                            <th scope="col">Имя</th>
                            <th scope="col">Цена</th>
                            <th scope="col">Кол-во</th>
                            <th scope="col">Сумма</th>
                            <th scope="col">&times;</th>
                        </tr></thead><tbody>`;
        for (i in data.basket) {
            let itemCost = Math.round((data.basket[i].price * data.basket[i].quantity)*100)/100;
            basketCost += itemCost;
            basketItems += `<tr>
                                 <td class="text-center text-break"><b>${data.basket[i].name}</b></td>
                                 <td class="text-center">${data.basket[i].price}</td>
                                 <td class="text-center"><input class="text-right" type="number" name="basket[${data.basket[i].id}]" value="${data.basket[i].quantity}" min="1" required style="width: 80px; max-height: 20px"></td>
                                 <td class="text-center">${itemCost}</td>
                                 <td class="text-center"><button class="btn btn-sm btn-outline-danger btn-del-item" type="button" data-id="${data.basket[i].id}">&times;</button></td>
                             </tr>`;
        }
        basketItems += `</tbody></table>`;
        basketItems += `<div class="col text-right"><b>Итого: ${Math.round(basketCost*100)/100}</b></div>`;
        basketItems += `<div class="col text-center"><button type="submit" class="btn btn-sm btn-success">Применить изменения</button></div>`;
    }
    $('.modal_basket_show_goods').html(basketItems);
    $('#modal-basket-show').modal('show');
}
