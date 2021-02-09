$(function() {
    const $finishForm = $('#finishForm');
    $(document).on('click', 'button.code-finish-todo', function() {
        $finishForm.find('input[name="id"]').val($(this).val());
        $finishForm.submit();
    });
});