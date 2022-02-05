//Сортировка в таблице товаров и переключение стрелки направления
$(document).on("click", ".sortingGoodsTable", function() {
    let sortColName = $(this).data('sort_col_name');
    let sortColNameText = $(this).data('sort_col_name_text');
    if (sortColName === 'name') {
        let currSortByName = $('#sortByName').val();
        if (currSortByName === '') {
            $('#sortByName').val('asc');
            $(this).text(sortColNameText + " ▲");
        }
        if (currSortByName === 'asc') {
            $('#sortByName').val('desc');
            $(this).text(sortColNameText + " ▼");
        }
        if (currSortByName === 'desc') {
            $('#sortByName').val('');
            $(this).text(sortColNameText);
        }
    }
    if (sortColName === 'created_at') {
        let currSortByCreated = $('#sortByCreated').val();
        if (currSortByCreated === '') {
            $('#sortByCreated').val('asc');
            $(this).text(sortColNameText + " ▲");
        }
        if (currSortByCreated === 'asc') {
            $('#sortByCreated').val('desc');
            $(this).text(sortColNameText + " ▼");
        }
        if (currSortByCreated === 'desc') {
            $('#sortByCreated').val('');
            $(this).text(sortColNameText);
        }
    }
    if (sortColName === 'updated_at') {
        let currSortByUpdated = $('#sortByUpdated').val();
        if (currSortByUpdated === '') {
            $('#sortByUpdated').val('asc');
            $(this).text(sortColNameText + " ▲");
        }
        if (currSortByUpdated === 'asc') {
            $('#sortByUpdated').val('desc');
            $(this).text(sortColNameText + " ▼");
        }
        if (currSortByUpdated === 'desc') {
            $('#sortByUpdated').val('');
            $(this).text(sortColNameText);
        }
    }
    if (sortColName === 'price') {
        let currSortByPrice = $('#sortByPrice').val();
        if (currSortByPrice === '') {
            $('#sortByPrice').val('asc');
            $(this).text(sortColNameText + " ▲");
        }
        if (currSortByPrice === 'asc') {
            $('#sortByPrice').val('desc');
            $(this).text(sortColNameText + " ▼");
        }
        if (currSortByPrice === 'desc') {
            $('#sortByPrice').val('');
            $(this).text(sortColNameText);
        }
    }
    if (sortColName === 'value') {
        let currSortByValue = $('#sortByValue').val();
        if (currSortByValue === '') {
            $('#sortByValue').val('asc');
            $(this).text(sortColNameText + " ▲");
        }
        if (currSortByValue === 'asc') {
            $('#sortByValue').val('desc');
            $(this).text(sortColNameText + " ▼");
        }
        if (currSortByValue === 'desc') {
            $('#sortByValue').val('');
            $(this).text(sortColNameText);
        }
    }
    $('#fsp').submit();
});
//Сортировка в таблице товаров и переключение стрелки направления - end
