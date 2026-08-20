@extends('behin-layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-building"></i> مشخصات انبار</h4>
    <div>
        <a href="{{ route('inventory.warehouses.edit', $warehouse) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> ویرایش
        </a>
        <a href="{{ route('inventory.warehouses.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-right"></i> بازگشت
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">نام انبار</label>
                <p class="form-control-plaintext">{{ $warehouse->name }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">شماره تلفن</label>
                <p class="form-control-plaintext">{{ $warehouse->phone }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label fw-bold">آدرس</label>
                <p class="form-control-plaintext">{{ $warehouse->address }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">ثبت کننده</label>
                <p class="form-control-plaintext">{{ $warehouse->creator->name }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">ویرایشگران</label>
                <p class="form-control-plaintext">
                    @forelse($warehouse->editors as $editor)
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
                <p class="form-control-plaintext">{{ $warehouse->created_at->format('Y/m/d H:i') }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">تاریخ آخرین ویرایش</label>
                <p class="form-control-plaintext">{{ $warehouse->updated_at->format('Y/m/d H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
