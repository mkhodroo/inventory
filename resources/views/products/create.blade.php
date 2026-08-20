@extends('behin-layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-plus-circle"></i> ایجاد محصول جدید</h4>
    <a href="{{ route('inventory.products.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right"></i> بازگشت
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('inventory.products.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">نام محصول</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                    id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="code" class="form-label">کد محصول</label>
                <input type="text" class="form-control @error('code') is-invalid @enderror"
                    id="code" name="code" value="{{ old('code') }}" required>
                @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">دسته‌بندی</label>
                <select class="form-select @error('category_id') is-invalid @enderror"
                    id="category" name="category_id" required>
                    <option value="">-- انتخاب دسته‌بندی --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            @if($category->parent) └ @endif
                            {{ $category->name }} ({{ $category->main_code }})
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="unit" class="form-label">واحد شمارش</label>
                    <input type="text" class="form-control @error('unit') is-invalid @enderror"
                        id="unit" name="unit" value="{{ old('unit') }}" required>
                    @error('unit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="sku" class="form-label">شناسه محصول (SKU)</label>
                    <input type="text" class="form-control @error('sku') is-invalid @enderror"
                        id="sku" name="sku" value="{{ old('sku') }}" required>
                    @error('sku')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">وضعیت</label>
                    <select class="form-select @error('status') is-invalid @enderror"
                        id="status" name="status" required>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ old('status', 'available') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">قیمت خرید (ریال)</label>
                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                        id="price" name="price" value="{{ old('price', 0) }}" min="0" step="1" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> ذخیره
            </button>
        </form>
    </div>
</div>
@endsection
