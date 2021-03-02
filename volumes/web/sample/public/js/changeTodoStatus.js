$(function() {
    const $changeStatus = $('#changeStatus');
    $(document).on('click', 'button.code-change-status', function() {
        $changeStatus.find('input[name="id"]').val($(this).val());
        $changeStatus.submit();
    });
});