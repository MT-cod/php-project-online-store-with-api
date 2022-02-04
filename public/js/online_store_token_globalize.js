//Подключаем токен для запросов ajax глобально
const csrf_token = $('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': csrf_token
    }});
