@extends('behin-layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-person-check"></i> مشخصات تحویل گیرنده</h4>
    <div>
        <a href="{{ route('inventory.receivers.edit', $receiver) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> ویرایش
        </a>
        <a href="{{ route('inventory.receivers.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-right"></i> بازگشت
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">نام</label>
                <p class="form-control-plaintext">{{ $receiver->name }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">کاربر متصل</label>
                <p class="form-control-plaintext">{{ $receiver->user->name ?? '-' }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">وضعیت</label>
                <p class="form-control-plaintext">
                    @if($receiver->is_active)
                        <span class="badge bg-success">فعال</span>
                    @else
                        <span class="badge bg-secondary">غیرفعال</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">تراز کالاهای تحویل داده شده</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ردیف</th>
                        <th>محصول</th>
                        <th>تحویل داده شده</th>
                        <th>تسویه شده</th>
                        <th>باقی مانده دست این فرد</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($balances as $balance)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $balance['product']->name }} ({{ $balance['product']->main_code }})</td>
                            <td><span class="badge bg-info">{{ $balance['delivered'] }}</span></td>
                            <td><span class="badge bg-success">{{ $balance['settled'] }}</span></td>
                            <td>
                                <span class="badge {{ $balance['remaining'] > 0 ? 'bg-warning text-dark' : 'bg-secondary' }} fs-6">
                                    {{ $balance['remaining'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">کالایی به این فرد تحویل داده نشده است.</td>
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
                <h5 class="mb-0"><i class="bi bi-box-arrow-right"></i> تاریخچه تحویل</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>انبار</th>
                                <th>محصول</th>
                                <th>تعداد</th>
                                <th>دلیل</th>
                                <th>تاریخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($exits as $exit)
                                <tr>
                                    <td>{{ $exit->warehouse->name }}</td>
                                    <td>{{ $exit->product->name }}</td>
                                    <td><span class="badge bg-danger">-{{ $exit->quantity }}</span></td>
                                    <td>{{ $exit->exitReason->name }}</td>
                                    <td>{{ $exit->created_at->format('Y/m/d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">تحویلی ثبت نشده است.</td>
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
                <h5 class="mb-0"><i class="bi bi-journal-check"></i> تاریخچه تسویه</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>محصول</th>
                                <th>تعداد</th>
                                <th>دلیل</th>
                                <th>تاریخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($settlements as $settlement)
                                <tr>
                                    <td>{{ $settlement->product->name }}</td>
                                    <td><span class="badge bg-success">{{ $settlement->quantity }}</span></td>
                                    <td>{{ $settlement->settlementReason->name }}</td>
                                    <td>{{ $settlement->created_at->format('Y/m/d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">تسویه‌ای ثبت نشده است.</td>
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
