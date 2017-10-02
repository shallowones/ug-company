$(function () {
    $('[name=saveDraft]').on('click', function () {
        $('input, select').removeAttr('required');
    });
});