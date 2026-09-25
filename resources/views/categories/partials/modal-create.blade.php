<form id="inventory-category-modal-form" action="{{ route('inventory.categories.store') }}" method="POST">
    @csrf

    <div id="inventory-category-modal-errors" class="alert alert-danger d-none"></div>

    <div class="mb-3">
        <label for="inventory-category-name" class="form-label">نام دسته‌بندی</label>
        <input type="text" class="form-control" id="inventory-category-name" name="name" required>
        <div class="invalid-feedback" data-error-for="name"></div>
    </div>

    <div class="mb-3">
        <label for="inventory-category-code" class="form-label">کد دسته‌بندی</label>
        <input type="text" class="form-control" id="inventory-category-code" name="code" required>
        <div class="invalid-feedback" data-error-for="code"></div>
    </div>

    <div class="mb-3">
        <label for="inventory-category-parent" class="form-label">دسته‌بندی والد</label>
        <select class="form-control" id="inventory-category-parent" name="parent_id">
            <option value="">بدون والد (دسته‌بندی اصلی)</option>
            @foreach($parentCategories as $parentCategory)
                <option value="{{ $parentCategory->id }}">
                    @if($parentCategory->parent)└ @endif{{ $parentCategory->name }} ({{ $parentCategory->code }})
                </option>
            @endforeach
        </select>
        <div class="invalid-feedback" data-error-for="parent_id"></div>
    </div>

    <div class="text-left">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-check"></i> ذخیره دسته‌بندی
        </button>
    </div>
</form>

<script>
    (function () {
        var $form = $('#inventory-category-modal-form');
        if (!$form.length) {
            return;
        }

        var $modal = $form.closest('.modal');
        var storeUrl = $form.attr('action');

        $form.off('submit').on('submit', function (event) {
            event.preventDefault();

            $form.find('.is-invalid').removeClass('is-invalid');
            $form.find('.invalid-feedback').text('');
            $('#inventory-category-modal-errors').addClass('d-none').text('');

            send_ajax_formdata_request(
                storeUrl,
                new FormData($form[0]),
                function (response) {
                    show_message(response.message || 'دسته‌بندی با موفقیت ثبت شد.');
                    $(document).trigger('inventory:category-created', [response.category]);
                    $modal.modal('hide');
                },
                function (xhr) {
                    hide_loading();

                    var response = xhr.responseJSON || {};
                    var errors = response.errors || {};
                    var messages = [];

                    $.each(errors, function (field, fieldMessages) {
                        var message = Array.isArray(fieldMessages) ? fieldMessages[0] : fieldMessages;
                        messages.push(message);
                        $form.find('[name="' + field + '"]').addClass('is-invalid');
                        $form.find('[data-error-for="' + field + '"]').text(message);
                    });

                    if (messages.length) {
                        $('#inventory-category-modal-errors').removeClass('d-none').text(messages.join(' | '));
                        return;
                    }

                    show_error(xhr);
                }
            );
        });

        setTimeout(function () {
            $form.find('[name="name"]').trigger('focus');
        }, 300);
    })();
</script>
