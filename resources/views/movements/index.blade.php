@extends('behin-layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fa fa-arrow-left-right"></i> لیست کامل ورود و خروج کالا</h4>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">فیلتر بر اساس انبار 123</label>
                <select name="warehouse_id" class="form-select">
                    <option value="">همه انبارها</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                            {{ $warehouse->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">فیلتر بر اساس محصول</label>
                <select name="product_id" class="form-select">
                    <option value="">همه محصولات</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">نوع عملیات</label>
                <select name="type" class="form-select">
                    <option value="">همه</option>
                    <option value="entry" {{ request('type') == 'entry' ? 'selected' : '' }}>ورود</option>
                    <option value="exit" {{ request('type') == 'exit' ? 'selected' : '' }}>خروج</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">جستجو</label>
                <input type="text" name="search" class="form-control" placeholder="نام محصول یا انبار..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-secondary me-2">
                    <i class="fa fa-funnel"></i> فیلتر
                </button>
                <a href="{{ route('inventory.movements.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-x-lg"></i>
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
                        <th>نوع</th>
                        <th>انبار</th>
                        <th>محصول</th>
                        <th>تعداد</th>
                        <th>تحویل گیرنده</th>
                        <th>دلیل</th>
                        <th>ثبت کننده</th>
                        <th>تاریخ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paginated as $index => $movement)
                        <tr>
                            <td>{{ $paginated->firstItem() + $index }}</td>
                            <td>
                                @if($movement->type === 'entry')
                                    <span class="badge bg-success">ورود</span>
                                @else
                                    <span class="badge bg-danger">خروج</span>
                                @endif
                            </td>
                            <td>{{ $movement->warehouse->name }}</td>
                            <td>{{ $movement->product->name }}</td>
                            <td>{{ $movement->quantity }}</td>
                            <td>{{ $movement->receiver_name ?? '-' }}</td>
                            <td>{{ $movement->reason_name }}</td>
                            <td>{{ $movement->creator->name }}</td>
                            <td>{{ $movement->created_at->format('Y/m/d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">هیچ عملیاتی ثبت نشده است.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $paginated->links() }}
        </div>
    </div>
</div>
@endsection
