@extends('behin-layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="mb-0"><i class="fa fa-plus-circle"></i> ثبت ورود کالا</h4>
        <p class="text-muted small mb-0">نام یا کد کالا را وارد کنید؛ پس از وارد کردن ۳ کاراکتر، نتایج جستجو نمایش داده می‌شود.</p>
    </div>
    <div class="card-body">
        <form id="entry-form" action="{{ route('inventory.entries.store') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" id="product_id" value="{{ old('product_id', $selectedProduct?->id) }}">

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="warehouse_id" class="form-label">انبار</label>
                    <select class="form-control" id="warehouse_id" name="warehouse_id" required>
                        <option value="">-- انتخاب انبار --</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-5 mb-3">
                    <label for="product-search" class="form-label">کالا (نام یا کد)</label>
                    <div class="position-relative">
                        <div class="input-group">
                            <input type="text" class="form-control" id="product-search" autocomplete="off"
                                placeholder="حداقل ۳ کاراکتر از نام یا کد کالا..."
                                value="{{ old('product_code', $selectedProduct?->name) }}">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                            </div>
                        </div>
                        <div id="product-search-results" class="list-group position-absolute"
                            style="z-index: 2000; width: 100%; max-height: 320px; overflow-y: auto; display: none;"></div>
                    </div>
                    <small class="text-muted">با تایپ ۳ کاراکتر، ۵ نتیجه اول نمایش داده می‌شود.</small>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="quantity" class="form-label">تعداد</label>
                    <input type="number" class="form-control" id="quantity" name="quantity"
                        value="{{ old('quantity') }}" min="1" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">کالای انتخاب‌شده</label>
                    <div id="selected-product">
                        @if($selectedProduct)
                            <div class="alert alert-success mb-0 py-2">
                                <strong>{{ $selectedProduct->name }}</strong>
                                <span class="d-block small">کد اصلی: {{ $selectedProduct->main_code }} | واحد: {{ $selectedProduct->unit }}</span>
                            </div>
                        @else
                            <div class="alert alert-secondary mb-0 py-2">هنوز کالایی انتخاب نشده است.</div>
                        @endif
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="entry_reason_id" class="form-label">دلیل ورود</label>
                    <div class="input-group">
                        <select class="form-control" id="entry_reason_id" name="entry_reason_id" required>
                            <option value="">-- انتخاب دلیل --</option>
                            @foreach($entryReasons as $reason)
                                <option value="{{ $reason->id }}" {{ old('entry_reason_id') == $reason->id ? 'selected' : '' }}>{{ $reason->name }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-primary" id="open-entry-reason-modal"
                                title="افزودن دلیل ورود">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> ثبت ورود</button>
        </form>
    </div>
</div>

<script>
    $(function () {
        var searchUrl = @json(route('inventory.products.search'));
        var createProductUrl = @json(route('inventory.products.modal-create'));
        var createReasonUrl = @json(route('inventory.entry-reasons.modal-create'));

        var $form = $('#entry-form');
        var $search = $('#product-search');
        var $results = $('#product-search-results');
        var $productId = $('#product_id');
        var $selectedBox = $('#selected-product');

        var searchTimer = null;
        var lastTerm = '';
        var selectedLabel = '';
        var matchedProducts = [];

        function escapeHtml(value) {
            return String(value === null || value === undefined ? '' : value)
                .replace(/[&<>"']/g, function (character) {
                    return {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#039;'
                    }[character];
                });
        }

        function setSelectedBox(product) {
            var categories = (product.categories || []).map(function (category) {
                return category.name;
            }).join('، ');

            var html = '<div class="alert alert-success mb-0 py-2">'
                + '<strong>' + escapeHtml(product.name) + '</strong>'
                + '<span class="d-block small">کد اصلی: ' + escapeHtml(product.main_code || product.code)
                + ' | واحد: ' + escapeHtml(product.unit)
                + (categories ? ' | دسته‌بندی: ' + escapeHtml(categories) : '')
                + '</span></div>';

            $selectedBox.html(html);
        }

        function hideResults() {
            $results.hide().empty();
        }

        function selectProduct(product) {
            $productId.val(product.id);
            selectedLabel = product.name + ' - ' + (product.main_code || product.code);
            $search.val(selectedLabel);
            setSelectedBox(product);
            hideResults();
        }

        function renderResults(products, term) {
            $results.empty();

            if (!products.length) {
                $results.append(
                    $('<div class="list-group-item text-muted"></div>')
                        .text('کالایی با نام یا کد «' + term + '» پیدا نشد.')
                );
            }

            $.each(products, function (index, product) {
                var $item = $('<button type="button" class="list-group-item list-group-item-action"></button>')
                    .attr('data-product-index', index);

                $item.append($('<strong></strong>').text(product.name + ' (' + (product.main_code || product.code) + ')'));
                $item.append($('<span class="d-block small text-muted"></span>').text('واحد: ' + product.unit));

                $results.append($item);
            });

            $results.append(
                $('<button type="button" class="list-group-item list-group-item-action text-primary" data-action="create-product"></button>')
                    .html('<i class="fa fa-plus"></i> ثبت کالای جدید')
            );

            $results.show();
        }


        function searchProducts(term) {
            matchedProducts = [];

            $.ajax({
                url: searchUrl,
                method: 'GET',
                dataType: 'json',
                data: { term: term },
                success: function (response) {
                    if (term !== lastTerm) {
                        return;
                    }

                    matchedProducts = response.products || [];
                    renderResults(matchedProducts, term);
                },
                error: function () {
                    hideResults();
                }
            });
        }

        $search.on('input', function () {
            var term = $.trim($search.val());

            if (term !== selectedLabel) {
                selectedLabel = '';
                $productId.val('');
            }

            clearTimeout(searchTimer);

            if (term.length < 3) {
                hideResults();
                return;
            }

            searchTimer = setTimeout(function () {
                lastTerm = term;
                searchProducts(term);
            }, 300);
        });

        $search.on('keydown', function (event) {
            if (event.key === 'Escape') {
                hideResults();
            }
        });

        $search.on('blur', function () {
            setTimeout(hideResults, 200);
        });

        $results.on('click', '[data-product-index]', function () {
            var product = matchedProducts[parseInt($(this).attr('data-product-index'), 10)];

            if (product) {
                selectProduct(product);
            }
        });

        $results.on('click', '[data-action="create-product"]', function () {
            var term = $.trim($search.val());
            hideResults();
            open_admin_modal(createProductUrl + '?term=' + encodeURIComponent(term), 'تعریف کالای جدید');
        });

        $(document)
            .off('inventory:product-created.inventoryEntry')
            .on('inventory:product-created.inventoryEntry', function (event, product) {
                if (product) {
                    selectProduct(product);
                }
            });

        $(document)
            .off('inventory:entry-reason-created.inventoryEntry')
            .on('inventory:entry-reason-created.inventoryEntry', function (event, entryReason) {
                if (!entryReason) {
                    return;
                }

                var $select = $('#entry_reason_id');

                if (!$select.find('option[value="' + entryReason.id + '"]').length) {
                    $select.append($('<option></option>').val(entryReason.id).text(entryReason.name));
                }

                $select.val(entryReason.id);
            });

        $(document).on('hidden.bs.modal', '.modal[id^="admin-modal-"]', function () {
            var term = $.trim($search.val());

            if ($productId.val() || term.length < 3) {
                return;
            }

            setTimeout(function () {
                if ($('.modal.show').length) {
                    return;
                }

                lastTerm = term;
                searchProducts(term);
            }, 250);
        });

        $('#open-entry-reason-modal').on('click', function () {
            open_admin_modal(createReasonUrl, 'افزودن دلیل ورود');
        });

        $form.on('submit', function (event) {
            if ($productId.val()) {
                return;
            }

            event.preventDefault();
            toastr.error('ابتدا کالا را جستجو و انتخاب کنید.');
            $search.trigger('focus');
        });

        $search.trigger('focus');
    });
</script>
@endsection
