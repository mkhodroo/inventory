@extends('behin-layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-pencil"></i> ویرایش دسته‌بندی: {{ $category->name }}</h4>
    <a href="{{ route('inventory.categories.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right"></i> بازگشت
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('inventory.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">نام دسته‌بندی</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                    id="name" name="name" value="{{ old('name', $category->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="code" class="form-label">کد دسته‌بندی</label>
                <input type="text" class="form-control @error('code') is-invalid @enderror"
                    id="code" name="code" value="{{ old('code', $category->code) }}" required>
                @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="parent_id" class="form-label">دسته‌بندی والد</label>
                <select class="form-select @error('parent_id') is-invalid @enderror"
                    id="parent_id" name="parent_id">
                    <option value="">بدون والد (دسته‌بندی اصلی)</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                            @if($parent->parent) &nbsp;&nbsp;&nbsp;└ @endif
                            {{ $parent->name }} ({{ $parent->code }})
                        </option>
                    @endforeach
                </select>
                @error('parent_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> بروزرسانی
            </button>
        </form>
    </div>
</div>
@endsection
