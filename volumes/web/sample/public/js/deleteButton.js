$(function() {
    const $deleteForm = $('#deleteForm');
    $(document).on('click', 'button.code-delete-todo', function() {
        $deleteForm.find('input[name="id"]').val($(this).val());
        $deleteForm.submit();
    });
});