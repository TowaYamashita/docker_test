$(function() {
    const $editForm = $('#editForm');
    $(document).on('click', 'button.code-edit-todo', function() {
        $editForm.find('input[name="id"]').val($(this).val());
        $editForm.submit();
    });
});