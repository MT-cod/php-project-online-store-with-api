//Подключаем токен для запросов ajax глобально
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }});


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

$(document).on("click", ".btn-modal_goods_show", function() {
    var id = $(this).data('id');
    $.ajax({
        url: '/goods/' + id,
        method: "get",
        success: function(data, textStatus, jqXHR){
            //var name = data.name;
            $('.modal-title').html('<b>' + data.name + '</b>');
            $('.modal_goods_show_name').html(data.name);
            $('.modal_goods_show_slug').html(data.slug);
            $('.modal_goods_show_description').html(data.description);
            $('.modal_goods_show_price').html(data.price);
            $('.modal_goods_show_category').html(data.category);
            $('.modal_goods_show_created_at').html(data.created_at);
            $('.modal_goods_show_updated_at').html(data.updated_at);
            $('.modal_goods_show_additional_chars').html(`${data.additional_chars.map((char) => {return `<b>${char.name}</b> (${char.value})<br/>`;}).join``}`);
            $('#modalItem-show').modal('show');
        //alert(textStatus);
    },
    error: function(jqXHR, textStatus, errorThrown){

    }});
});


//$("input[name='showName']").val(name);
