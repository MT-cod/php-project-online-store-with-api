//Общие функции

function inModalErrorFlashing(respErrors, spanClass)
{
    let errors = '';
    if (typeof respErrors == 'string') {
        errors = respErrors;
    }
    if (typeof respErrors == 'object') {
        Object.entries(respErrors).forEach(function (errNote) {
            errors += errNote[1][0] + '<br>';
        });
    }
    $(spanClass).html(
        '<div class="alert alert-danger text-center" role="alert">' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span></button>' + errors + '</div>'
    );
    return true;
}
