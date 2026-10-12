{{--
    فیلتر پویا بر اساس مشخصات کالا.
    نام فیلدها: specifications[INDEX][name] و specifications[INDEX][value]
--}}
@php
    $filterSpecs = old('specifications', request('specifications', []));
    if (! is_array($filterSpecs)) {
        $filterSpecs = [];
    }
@endphp

<div class="col-12 mb-2" id="specifications-filter-wrapper">
    <label class="form-label fw-bold">فیلتر بر اساس مشخصات</label>
    <p class="text-muted small mb-2">
        <i class="fa fa-circle-info"></i>
        می‌توانید بر اساس ویژگی‌ها فیلتر کنید، مثلاً نام ویژگی «رنگ» و مقدار «سبز».
    </p>

    <div id="specifications-filter-rows">
        @forelse($filterSpecs as $index => $spec)
            <div class="row g-2 mb-2 specification-filter-row">
                <div class="col-md-5">
                    <input type="text" name="specifications[{{ $index }}][name]"
                        class="form-control" placeholder="نام ویژگی (مثلاً رنگ)"
                        value="{{ is_array($spec) ? ($spec['name'] ?? '') : '' }}">
                </div>
                <div class="col-md-5">
                    <input type="text" name="specifications[{{ $index }}][value]"
                        class="form-control" placeholder="مقدار (مثلاً سبز)"
                        value="{{ is_array($spec) ? ($spec['value'] ?? '') : '' }}">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger w-100 remove-specification-filter-row">
                        <i class="fa fa-trash"></i> حذف
                    </button>
                </div>
            </div>
        @empty
        @endforelse
    </div>

    <button type="button" class="btn btn-outline-primary btn-sm" id="add-specification-filter-row">
        <i class="fa fa-plus"></i> افزودن فیلتر ویژگی
    </button>
</div>

<script>
    (function () {
        var wrapper = document.getElementById('specifications-filter-wrapper');
        if (!wrapper) {
            return;
        }

        var rowsContainer = document.getElementById('specifications-filter-rows');
        var addButton = document.getElementById('add-specification-filter-row');
        var index = rowsContainer.querySelectorAll('.specification-filter-row').length;

        function buildRow(index) {
            var row = document.createElement('div');
            row.className = 'row g-2 mb-2 specification-filter-row';
            row.innerHTML =
                '<div class="col-md-5">' +
                '<input type="text" name="specifications[' + index + '][name]" class="form-control" placeholder="نام ویژگی (مثلاً رنگ)">' +
                '</div>' +
                '<div class="col-md-5">' +
                '<input type="text" name="specifications[' + index + '][value]" class="form-control" placeholder="مقدار (مثلاً سبز)">' +
                '</div>' +
                '<div class="col-md-2">' +
                '<button type="button" class="btn btn-outline-danger w-100 remove-specification-filter-row">' +
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
            var target = event.target.closest('.remove-specification-filter-row');
            if (target) {
                target.closest('.specification-filter-row').remove();
            }
        });
    })();
</script>
