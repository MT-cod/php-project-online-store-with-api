//Окно изменения заказа
$(document).on("click", ".btn-modal_order_edit", function() {
    let id = $(this).data('id');
    $.ajax({
        url: '/orders/' + id + '/edit',
        method: "get",
        dataType: 'json',
        success: function(data) {
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
                let itemCost = Math.round((data.basket[i].pivot.price * data.basket[i].pivot.quantity) * 100) / 100;
                basketCost += itemCost;
                basketItems += `<tr>
                             <td class="text-center text-break"><b>${data.basket[i].name}</b></td>
                             <td class="text-center"><b>${data.basket[i].pivot.price}</b></td>
                             <td class="text-center"><b>${data.basket[i].pivot.quantity}</b></td>
                             <td class="text-center"><b>${itemCost}</b></td>
                         </tr>`;
            }
            basketItems += `</tbody></table>`;
            basketItems += `<div class="col text-right"><b>Итого: ${Math.round(basketCost * 100) / 100}</b></div>`;

            $('#modal_order_id').html('Заказ №' + data.id);
            $('#modal_order_name').html(data.name);
            $('#modal_order_email').html(data.email);
            $('#modal_order_phone').html(data.phone);
            $('#modal_order_address').html(data.address);
            $('#modal_order_comment').html(data.comment);
            $('#modal_order_basket').html(basketItems);
            $('.modal-order-form').attr('action', '/orders/' + id);
            if (data.completed == 1) {
                $('#status').val(0);
                $('#btn-order-complete').html('Вернуть в обработку ↺');
            } else {
                $('#status').val(1);
                $('#btn-order-complete').html('Завершить заказ ✓');
            }
            $('#modal-order-edit').modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown) {
        }});
});
//Окно изменения заказа - end
