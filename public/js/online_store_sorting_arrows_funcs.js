//Сортировка в таблице товаров и переключение стрелки направления
$(document).on("click", ".sortingGoodsTable", function() {
    let sortColName = $(this).data('sort_col_name');
    if (sortColName === 'name') {
        let currSortByName = $('#sortByName').val();
        if (currSortByName === '') {
            $('#sortByName').val('asc');
            $(this).text("Наименование товара ▲");
        }
        if (currSortByName === 'asc') {
            $('#sortByName').val('desc');
            $(this).text("Наименование товара ▼");
        }
        if (currSortByName === 'desc') {
            $('#sortByName').val('');
            $(this).text("Наименование товара");
        }
    }
    if (sortColName === 'created_at') {
        let currSortByCreated = $('#sortByCreated').val();
        if (currSortByCreated === '') {
            $('#sortByCreated').val('asc');
            $(this).text("Создан ▲");
        }
        if (currSortByCreated === 'asc') {
            $('#sortByCreated').val('desc');
            $(this).text("Создан ▼");
        }
        if (currSortByCreated === 'desc') {
            $('#sortByCreated').val('');
            $(this).text("Создан");
        }
    }
    if (sortColName === 'updated_at') {
        let currSortByUpdated = $('#sortByUpdated').val();
        if (currSortByUpdated === '') {
            $('#sortByUpdated').val('asc');
            $(this).text("Изменён ▲");
        }
        if (currSortByUpdated === 'asc') {
            $('#sortByUpdated').val('desc');
            $(this).text("Изменён ▼");
        }
        if (currSortByUpdated === 'desc') {
            $('#sortByUpdated').val('');
            $(this).text("Изменён");
        }
    }
    if (sortColName === 'price') {
        let currSortByPrice = $('#sortByPrice').val();
        if (currSortByPrice === '') {
            $('#sortByPrice').val('asc');
            $(this).text("Цена ▲");
        }
        if (currSortByPrice === 'asc') {
            $('#sortByPrice').val('desc');
            $(this).text("Цена ▼");
        }
        if (currSortByPrice === 'desc') {
            $('#sortByPrice').val('');
            $(this).text("Цена");
        }
    }
    $('#fsp').submit();
});
//Сортировка в таблице товаров и переключение стрелки направления - end

