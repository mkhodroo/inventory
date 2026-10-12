{{--
    ردیف‌های پویا «مشخصات کالا».
    متغیرهای اختیاری:
      $specs : مجموعه/آرایه مشخصات موجود برای پرکردن اولیه (حالت ویرایش یا old input)
    نام فیلدها: specifications[INDEX][name] و specifications[INDEX][value]
--}}
@php
    $specs = $specs ?? old('specifications', []);
@endphp

<div class="mb-3" id="specifications-wrapper">
    <label class="form-label fw-bold">مشخصات کالا</label>
    <p class="text-muted small mb-2">
        <i class="fa fa-circle-info"></i>
        ویژگی‌های دلخواه کالا را وارد کنید، مثلاً «رنگ: سبز» یا «قطر: 12».
    </p>

    <div id="specifications-rows">
        @forelse($specs as $index => $spec)
            <div class="row g-2 mb-2 specification-row">
                <div class="col-md-5">
                    <input type="text" name="specifications[{{ $index }}][name]"
                        class="form-control" placeholder="نام ویژگی (مثلاً رنگ)"
                        value="{{ is_array($spec) ? ($spec['name'] ?? '') : ($spec->name ?? '') }}">
                </div>
                <div class="col-md-5">
                    <input type="text" name="specifications[{{ $index }}][value]"
                        class="form-control" placeholder="مقدار (مثلاً سبز)"
                        value="{{ is_array($spec) ? ($spec['value'] ?? '') : ($spec->value ?? '') }}">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger w-100 remove-specification-row">
                        <i class="fa fa-trash"></i> حذف
                    </button>
                </div>
            </div>
        @empty
        @endforelse
    </div>

    <button type="button" class="btn btn-outline-primary btn-sm" id="add-specification-row">
        <i class="fa fa-plus"></i> افزودن ویژگی
    </button>
</div>

<script>
    (function () {
        var wrapper = document.getElementById('specifications-wrapper');
        if (!wrapper) {
            return;
        }

        var rowsContainer = document.getElementById('specifications-rows');
        var addButton = document.getElementById('add-specification-row');
        var index = rowsContainer.querySelectorAll('.specification-row').length;

        function buildRow(index) {
            var row = document.createElement('div');
            row.className = 'row g-2 mb-2 specification-row';
            row.innerHTML =
                '<div class="col-md-5">' +
                '<input type="text" name="specifications[' + index + '][name]" class="form-control" placeholder="نام ویژگی (مثلاً رنگ)">' +
                '</div>' +
                '<div class="col-md-5">' +
                '<input type="text" name="specifications[' + index + '][value]" class="form-control" placeholder="مقدار (مثلاً سبز)">' +
                '</div>' +
                '<div class="col-md-2">' +
                '<button type="button" class="btn btn-outline-danger w-100 remove-specification-row">' +
                '<i class="fa fa-trash"></i> حذف' +
                '</button>' +
                '</div>';
            return row;
        }

        if (addButton) {
            addButton.addEventListener('click', function () {
                rowsContainer.appendChild(buildRow(index));
                index++;
            });
        }

        rowsContainer.addEventListener('click', function (event) {
            var target = event.target.closest('.remove-specification-row');
            if (target) {
                target.closest('.specification-row').remove();
            }
        });
    })();
</script>

