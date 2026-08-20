@extends('behin-layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-tags"></i> مشخصات دسته‌بندی</h4>
    <div>
        <a href="{{ route('inventory.categories.edit', $category) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> ویرایش
        </a>
        <a href="{{ route('inventory.categories.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-right"></i> بازگشت
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">نام دسته‌بندی</label>
                <p class="form-control-plaintext">{{ $category->name }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">کد</label>
                <p class="form-control-plaintext">{{ $category->code }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">کد اصلی</label>
                <p class="form-control-plaintext">{{ $category->main_code }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">دسته‌بندی والد</label>
                <p class="form-control-plaintext">{{ $category->parent?->name ?? '-' }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">ثبت کننده</label>
                <p class="form-control-plaintext">{{ $category->creator->name }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">ویرایشگران</label>
                <p class="form-control-plaintext">
                    @forelse($category->editors as $editor)
                        <span class="badge bg-secondary">{{ $editor->name }}</span>
                    @empty
                        <span class="text-muted">بدون ویرایشگر</span>
                    @endforelse
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">تاریخ ایجاد</label>
                <p class="form-control-plaintext">{{ $category->created_at->format('Y/m/d H:i') }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">تاریخ آخرین ویرایش</label>
                <p class="form-control-plaintext">{{ $category->updated_at->format('Y/m/d H:i') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-box"></i> محصولات این دسته‌بندی ({{ $category->products->count() }})</h5>
    </div>
    <div class="card-body">
        @if($category->products->isEmpty())
            <p class="text-muted text-center">هیچ محصولی در این دسته‌بندی وجود ندارد.</p>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ردیف</th>
                            <th>نام محصول</th>
                            <th>کد اصلی</th>
                            <th>واحد</th>
                            <th>SKU</th>
                            <th>وضعیت</th>
                            <th>قیمت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($category->products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->main_code }}</td>
                                <td>{{ $product->unit }}</td>
                                <td>{{ $product->sku }}</td>
                                <td>
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
                                </td>
                                <td>{{ number_format($product->price) }} ریال</td>
                                <td>
                                    <a href="{{ route('inventory.products.show', $product) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
