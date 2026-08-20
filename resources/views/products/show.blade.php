@extends('behin-layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-box"></i> مشخصات محصول</h4>
    <div>
        <a href="{{ route('inventory.products.edit', $product) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> ویرایش
        </a>
        <a href="{{ route('inventory.products.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-right"></i> بازگشت
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">نام محصول</label>
                <p class="form-control-plaintext">{{ $product->name }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">کد محصول</label>
                <p class="form-control-plaintext">{{ $product->code }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">کد اصلی</label>
                <p class="form-control-plaintext">{{ $product->main_code }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">واحد شمارش</label>
                <p class="form-control-plaintext">{{ $product->unit }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">شناسه محصول (SKU)</label>
                <p class="form-control-plaintext">{{ $product->sku }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">قیمت خرید</label>
                <p class="form-control-plaintext">{{ number_format($product->price) }} ریال</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">وضعیت</label>
                <p class="form-control-plaintext">
                    @php
                        $statusClasses = [
                            'available' => 'bg-success',
                            'consumed' => 'bg-secondary',
                            'consignment' => 'bg-warning text-dark',
                            'sold' => 'bg-danger',
                        ];
                    @endphp
                    <span class="badge {{ $statusClasses[$product->status] ?? 'bg-secondary' }}">
                        {{ $product->status_label }}
                    </span>
                </p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">ثبت کننده</label>
                <p class="form-control-plaintext">{{ $product->creator->name }}</p>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">دسته‌بندی‌ها</label>
            <p class="form-control-plaintext">
                @forelse($product->categories as $category)
                    <span class="badge bg-info">{{ $category->name }}</span>
                @empty
                    <span class="text-muted">بدون دسته‌بندی</span>
                @endforelse
            </p>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">ویرایشگران</label>
            <p class="form-control-plaintext">
                @forelse($product->editors as $editor)
                    <span class="badge bg-secondary">{{ $editor->name }}</span>
                @empty
                    <span class="text-muted">بدون ویرایشگر</span>
                @endforelse
            </p>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">تاریخ ایجاد</label>
                <p class="form-control-plaintext">{{ $product->created_at->format('Y/m/d H:i') }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">تاریخ آخرین ویرایش</label>
                <p class="form-control-plaintext">{{ $product->updated_at->format('Y/m/d H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
