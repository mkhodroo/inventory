@extends('behin-layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-clipboard-data"></i> جزئیات موجودی: {{ $product->name }}</h4>
    <a href="{{ route('inventory.stock.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right"></i> بازگشت
    </a>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">موجودی به تفکیک انبار</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ردیف</th>
                        <th>نام انبار</th>
                        <th>موجودی</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($warehouseStocks as $stock)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $stock->warehouse->name }}</td>
                            <td>
                                <span class="badge bg-success fs-6">{{ $stock->quantity }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">موجودی در هیچ انباری وجود ندارد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-arrow-down-circle"></i> تاریخچه ورود</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>انبار</th>
                                <th>تعداد</th>
                                <th>دلیل</th>
                                <th>تاریخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($entries as $entry)
                                <tr>
                                    <td>{{ $entry->warehouse->name }}</td>
                                    <td><span class="badge bg-success">+{{ $entry->quantity }}</span></td>
                                    <td>{{ $entry->entryReason->name }}</td>
                                    <td>{{ $entry->created_at->format('Y/m/d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">ورودی ثبت نشده است.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-arrow-up-circle"></i> تاریخچه خروج</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>انبار</th>
                                <th>تعداد</th>
                                <th>تحویل گیرنده</th>
                                <th>دلیل</th>
                                <th>تاریخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($exits as $exit)
                                <tr>
                                    <td>{{ $exit->warehouse->name }}</td>
                                    <td><span class="badge bg-danger">-{{ $exit->quantity }}</span></td>
                                    <td>{{ $exit->receiver->name ?? '-' }}</td>
                                    <td>{{ $exit->exitReason->name }}</td>
                                    <td>{{ $exit->created_at->format('Y/m/d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">خروجی ثبت نشده است.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
