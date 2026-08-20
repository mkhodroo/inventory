@extends('behin-layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-plus-circle"></i> ایجاد انبار جدید</h4>
    <a href="{{ route('inventory.warehouses.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right"></i> بازگشت
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('inventory.warehouses.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">نام انبار</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                    id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">آدرس</label>
                <textarea class="form-control @error('address') is-invalid @enderror"
                    id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">شماره تلفن</label>
                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                    id="phone" name="phone" value="{{ old('phone') }}" required>
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> ذخیره
            </button>
        </form>
    </div>
</div>
@endsection
