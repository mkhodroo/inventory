@extends('behin-layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fa fa-box"></i> مدیریت محصولات</h4>
    <a href="{{ route('inventory.products.create') }}" class="btn btn-primary">
        <i class="fa fa-plus-lg"></i> ایجاد محصول جدید
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('inventory.products.filter') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">فیلتر نام محصول</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="نام محصول...">
            </div>
            <div class="col-md-4">
                <label class="form-label">فیلتر کد اصلی</label>
                <input type="text" name="main_code" value="{{ request('main_code') }}" class="form-control" placeholder="کد اصلی...">
            </div>
            <div class="col-md-4">
                <label class="form-label">فیلتر وضعیت</label>
                <select name="status" class="form-select">
                    <option value="">همه-status</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>موجود</option>
                    <option value="consumed" {{ request('status') == 'consumed' ? 'selected' : '' }}>مصرف شده</option>
                    <option value="consignment" {{ request('status') == 'consignment' ? 'selected' : '' }}>امانی</option>
                    <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>فروش رفته</option>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-secondary me-2">
                    <i class="fa fa-funnel"></i> فیلتر
                </button>
                <a href="{{ route('inventory.products.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-x-lg"></i> پاک کردن
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ردیف</th>
                        <th>نام محصول</th>
                        <th>کد اصلی</th>
                        <th>واحد</th>
                        <!-- <th>SKU</th> -->
                        <th>وضعیت</th>
                        <th>قیمت خرید</th>
                        <th>ثبت کننده</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $product->name }}
                                <br>
                                @foreach($product->categories as $category)
                                    <span class="badge bg-info">{{ $category->name }}</span>
                                @endforeach
                            </td>
                            <td>{{ $product->main_code }}</td>
                            <td>{{ $product->unit }}</td>
                            <!-- <td>{{ $product->sku }}</td> -->
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
                            <td>{{ $product->creator->name }}</td>
                            <td class="text-nowrap">
                                <div class="d-flex gap-1">
                                    <a href="{{ route('inventory.products.show', $product) }}" class="btn btn-sm btn-info">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('inventory.products.edit', $product) }}" class="btn btn-sm btn-warning">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <form action="{{ route('inventory.products.destroy', $product) }}" method="POST"
                                        onsubmit="return confirm('آیا از حذف این محصول اطمینان دارید؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">هیچ محصولی یافت نشد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
