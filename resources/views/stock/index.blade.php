@extends('behin-layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fa fa-clipboard-data"></i> گزارش موجودی کل</h4>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">فیلتر بر اساس محصول</label>
                <select name="product_id" class="form-select select2">
                    <option value="">همه محصولات</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} ({{ $product->main_code }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <button type="submit" class="btn btn-secondary me-2">
                    <i class="fa fa-funnel"></i> فیلتر
                </button>
                <a href="{{ route('inventory.stock.index') }}" class="btn btn-outline-secondary">
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
                        <th>موجودی کل</th>
                        <th>موجودی هر انبار</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks as $stock)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $stock->product->name }}</td>
                            <td>{{ $stock->product->main_code }}</td>
                            <td>
                                <span class="badge bg-success fs-6">{{ $stock->total_quantity }}</span>
                            </td>
                            <td>
                                @if(isset($warehouseStocks[$stock->product_id]))
                                    @foreach($warehouseStocks[$stock->product_id] as $ws)
                                        <span class="badge bg-info me-1 mb-1">
                                            {{ $ws->warehouse->name }}: {{ $ws->quantity }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('inventory.stock.show', $stock->product) }}" class="btn btn-sm btn-info">
                                    <i class="fa fa-eye"></i> جزئیات
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">هیچ موجودی یافت نشد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
