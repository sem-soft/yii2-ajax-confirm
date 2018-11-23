$(function () {

    $(document).on('beforeSubmit', 'form.ajax-confirmation', function ($e) {

        var $yiiForm = $(this);
        var url = $yiiForm.attr('data-confirm_url');

        $.ajax({
                type: 'post',
                url: url,
                data: $yiiForm.serializeArray()
            }
        )
            .done(function (data) {

                if (data.can) {
                    $e.currentTarget.submit();
                } else {
                    if (!confirm(data.question)) {
                        $e.stopPropagation();
                    } else {
                        $e.currentTarget.submit();
                    }

                }

            })
            .fail(function (jqXHR, textStatus) {
                alert(jqXHR.responseText);
            });

        return false;
    });

});