<form id="inventory-entry-reason-modal-form" action="{{ route('inventory.entry-reasons.store') }}" method="POST">
    @csrf

    <div id="inventory-entry-reason-modal-errors" class="alert alert-danger d-none"></div>

    <div class="mb-3">
        <label for="inventory-entry-reason-name" class="form-label">نام دلیل ورود</label>
        <input type="text" class="form-control" id="inventory-entry-reason-name" name="name"
            placeholder="مثلاً خرید، برگشت از فروش، امانت" required>
        <div class="invalid-feedback" data-error-for="name"></div>
    </div>

    <div class="text-left">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-check"></i> ذخیره دلیل
        </button>
    </div>
</form>

<script>
    (function () {
        var $form = $('#inventory-entry-reason-modal-form');
        if (!$form.length) {
            return;
        }

        var $modal = $form.closest('.modal');
        var storeUrl = $form.attr('action');

        $form.off('submit').on('submit', function (event) {
            event.preventDefault();

            $form.find('.is-invalid').removeClass('is-invalid');
            $form.find('.invalid-feedback').text('');
            $('#inventory-entry-reason-modal-errors').addClass('d-none').text('');

            send_ajax_formdata_request(
                storeUrl,
                new FormData($form[0]),
                function (response) {
                    show_message(response.message || 'دلیل ورود با موفقیت ثبت شد.');
                    $(document).trigger('inventory:entry-reason-created', [response.entry_reason]);
                    $modal.modal('hide');
                },
                function (xhr) {
                    hide_loading();

                    var response = xhr.responseJSON || {};
                    var errors = response.errors || {};

                    $.each(errors, function (field, fieldMessages) {
                        var message = Array.isArray(fieldMessages) ? fieldMessages[0] : fieldMessages;
                        $form.find('[name="' + field + '"]').addClass('is-invalid');
                        $form.find('[data-error-for="' + field + '"]').text(message);
                    });

                    if (!Object.keys(errors).length) {
                        show_error(xhr);
                    }
                }
            );
        });

        setTimeout(function () {
            $form.find('[name="name"]').trigger('focus');
        }, 300);
    })();
</script>
