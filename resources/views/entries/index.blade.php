@extends('behin-layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fa fa-arrow-down-circle"></i> ورود کالا به انبار</h4>
    <a href="{{ route('inventory.entries.create') }}" class="btn btn-primary">
        <i class="fa fa-plus-lg"></i> ثبت ورود جدید
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">فیلتر بر اساس انبار</label>
                <select name="warehouse_id" class="form-select">
                    <option value="">همه انبارها</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $loop->iteration }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                            {{ $warehouse->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">فیلتر بر اساس محصول</label>
                <select name="product_id" class="form-select">
                    <option value="">همه محصولات</option>
                    @foreach($products as $product)
                        <option value="{{ $loop->iteration }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} | {{ $product->main_code }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-secondary me-2">
                    <i class="fa fa-funnel"></i> فیلتر
                </button>
                <a href="{{ route('inventory.entries.index') }}" class="btn btn-outline-secondary">
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
                        <th>انبار</th>
                        <th>محصول</th>
                        <th>کد اصلی</th>
                        <th>تعداد</th>
                        <th>دلیل ورود</th>
                        <th>ثبت کننده</th>
                        <th>تاریخ</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $entry)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $entry->warehouse->name }}</td>
                            <td>{{ $entry->product->name }}</td>
                            <td>{{ $entry->product->main_code }}</td>
                            <td>{{ $entry->quantity }}</td>
                            <td>{{ $entry->entryReason->name }}</td>
                            <td>{{ $entry->creator->name }}</td>
                            <td>{{ $entry->created_at->format('Y/m/d H:i') }}</td>
                            <td class="text-nowrap">
                                <div class="d-flex gap-1">
                                    <a href="{{ route('inventory.entries.edit', $entry) }}" class="btn btn-sm btn-warning">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <form action="{{ route('inventory.entries.destroy', $entry) }}" method="POST"
                                        onsubmit="return confirm('آیا از حذف این ورود اطمینان دارید؟')">
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
                            <td colspan="8" class="text-center">هیچ ورودی ثبت نشده است.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
