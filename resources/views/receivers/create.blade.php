@extends('behin-layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-person-plus"></i> ایجاد تحویل گیرنده</h4>
    <a href="{{ route('inventory.receivers.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right"></i> بازگشت
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('inventory.receivers.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">نام تحویل گیرنده</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                        id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="user_id" class="form-label">کاربر متصل (اختیاری)</label>
                    <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id">
                        <option value="">-- بدون کاربر --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <div class="form-check">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" class="form-check-input @error('is_active') is-invalid @enderror"
                        id="is_active" name="is_active" value="1" checked>
                    <label for="is_active" class="form-check-label">فعال</label>
                    @error('is_active')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> ثبت
            </button>
        </form>
    </div>
</div>
@endsection
