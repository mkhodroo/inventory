@extends('behin-layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fa fa-box"></i> مدیریت محصولات</h4>
    <a href="{{ route('inventory.products.create') }}" class="btn btn-primary">
        <i class="fa fa-plus-lg"></i> ایجاد محصول جدید
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ردیف</th>
                        <th>نام محصول</th>
                        <th>دسته‌بندی‌ها</th>
                        <th>کد اصلی</th>
                        <th>واحد</th>
                        <th>SKU</th>
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
                            <td>{{ $product->name }}</td>
                            <td>
                                @foreach($product->categories as $category)
                                    <span class="badge bg-info">{{ $category->name }}</span>
                                @endforeach
                            </td>
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
