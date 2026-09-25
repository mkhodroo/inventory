<form id="inventory-product-modal-form" action="{{ route('inventory.products.store') }}" method="POST">
    @csrf

    <div id="inventory-product-modal-errors" class="alert alert-danger d-none"></div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="inventory-product-name" class="form-label">نام کالا</label>
            <input type="text" class="form-control" id="inventory-product-name" name="name" required>
            <div class="invalid-feedback" data-error-for="name"></div>
        </div>
        <div class="col-md-6 mb-3">
            <label for="inventory-product-code" class="form-label">کد کالا</label>
            <input type="text" class="form-control" id="inventory-product-code" name="code"
                value="{{ $prefilledCode }}" required>
            <small class="text-muted">کد اصلی با ترکیب کد دسته‌بندی و این کد ساخته می‌شود.</small>
            <div class="invalid-feedback" data-error-for="code"></div>
        </div>
    </div>

    <div class="mb-3">
        <label for="inventory-product-category" class="form-label">دسته‌بندی</label>
        <div class="input-group">
            <select class="form-control" id="inventory-product-category" name="category_id" required>
                <option value="">-- انتخاب دسته‌بندی --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">
                        @if($category->parent)└ @endif{{ $category->name }} ({{ $category->main_code }})
                    </option>
                @endforeach
            </select>
            <div class="input-group-append">
                <button type="button" class="btn btn-outline-primary" id="inventory-open-category-modal"
                    title="افزودن دسته‌بندی جدید">
                    <i class="fa fa-plus"></i> دسته‌بندی جدید
                </button>
            </div>
        </div>
        <div class="invalid-feedback d-block" data-error-for="category_id"></div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="inventory-product-unit" class="form-label">واحد شمارش</label>
            <input type="text" class="form-control" id="inventory-product-unit" name="unit"
                placeholder="مثلاً عدد، بسته، متر" required>
            <div class="invalid-feedback" data-error-for="unit"></div>
        </div>
        <div class="col-md-6 mb-3">
            <label for="inventory-product-sku" class="form-label">شناسه کالا (SKU)</label>
            <input type="text" class="form-control" id="inventory-product-sku" name="sku" required>
            <div class="invalid-feedback" data-error-for="sku"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="inventory-product-status" class="form-label">وضعیت</label>
            <select class="form-control" id="inventory-product-status" name="status" required>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" {{ $key === 'available' ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <div class="invalid-feedback" data-error-for="status"></div>
        </div>
        <div class="col-md-6 mb-3">
            <label for="inventory-product-price" class="form-label">قیمت خرید (ریال)</label>
            <input type="number" class="form-control" id="inventory-product-price" name="price"
                value="0" min="0" step="1" required>
            <div class="invalid-feedback" data-error-for="price"></div>
        </div>
    </div>

    <div class="text-left">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-check"></i> ذخیره کالا
        </button>
    </div>
</form>

<script>
    (function () {
        var $form = $('#inventory-product-modal-form');
        if (!$form.length) {
            return;
        }

        var $modal = $form.closest('.modal');
        var storeUrl = $form.attr('action');

        $form.off('submit').on('submit', function (event) {
            event.preventDefault();

            $form.find('.is-invalid').removeClass('is-invalid');
            $form.find('.invalid-feedback').text('');
            $('#inventory-product-modal-errors').addClass('d-none').text('');

            send_ajax_formdata_request(
                storeUrl,
                new FormData($form[0]),
                function (response) {
                    show_message(response.message || 'کالا با موفقیت ثبت شد.');
                    $(document).trigger('inventory:product-created', [response.product]);
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
                        $('#inventory-product-modal-errors').removeClass('d-none').text(messages.join(' | '));
                        return;
                    }

                    show_error(xhr);
                }
            );
        });

        $('#inventory-open-category-modal').off('click').on('click', function () {
            open_admin_modal('{{ route('inventory.categories.modal-create') }}', 'تعریف دسته‌بندی جدید');
        });

        $(document)
            .off('inventory:category-created.inventoryProductForm')
            .on('inventory:category-created.inventoryProductForm', function (event, category) {
                if (!category) {
                    return;
                }

                var label = (category.parent_name ? '└ ' : '') + category.name + ' (' + category.main_code + ')';
                var $select = $form.find('select[name="category_id"]');
                var $option = $select.find('option[value="' + category.id + '"]');

                if (!$option.length) {
                    $select.append($('<option>', {
                        value: category.id,
                        text: label
                    }));
                }

                $select.val(category.id);
            });

        setTimeout(function () {
            $form.find('[name="name"]').trigger('focus');
        }, 300);
    })();
</script>
