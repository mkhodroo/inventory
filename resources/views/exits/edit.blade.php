@extends('behin-layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-pencil"></i> ویرایش خروج کالا</h4>
    <a href="{{ route('inventory.exits.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right"></i> بازگشت
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('inventory.exits.update', $exit) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="warehouse_id" class="form-label">انبار</label>
                    <select class="form-select @error('warehouse_id') is-invalid @enderror"
                        id="warehouse_id" name="warehouse_id" required>
                        <option value="">-- انتخاب انبار --</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ old('warehouse_id', $exit->warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('warehouse_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="product_id" class="form-label">محصول</label>
                    <select class="form-select @error('product_id') is-invalid @enderror"
                        id="product_id" name="product_id" required>
                        <option value="">-- انتخاب محصول --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id', $exit->product_id) == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} ({{ $product->main_code }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="quantity" class="form-label">تعداد</label>
                    <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                        id="quantity" name="quantity" value="{{ old('quantity', $exit->quantity) }}" min="1" required>
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="receiver_id" class="form-label">تحویل گیرنده</label>
                    <select class="form-select @error('receiver_id') is-invalid @enderror"
                        id="receiver_id" name="receiver_id" required>
                        <option value="">-- انتخاب تحویل گیرنده --</option>
                        @foreach($receivers as $receiver)
                            <option value="{{ $receiver->id }}" {{ old('receiver_id', $exit->receiver_id) == $receiver->id ? 'selected' : '' }}>
                                {{ $receiver->name }}
                            </option>
                        @endforeach
                        @if($exit->receiver && ! $exit->receiver->is_active)
                            <option value="{{ $exit->receiver_id }}" selected>
                                {{ $exit->receiver->name }} (غیرفعال)
                            </option>
                        @endif
                    </select>
                    @error('receiver_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="exit_reason_id" class="form-label">دلیل خروج</label>
                    <select class="form-select @error('exit_reason_id') is-invalid @enderror"
                        id="exit_reason_id" name="exit_reason_id" required>
                        <option value="">-- انتخاب دلیل --</option>
                        @foreach($exitReasons as $reason)
                            <option value="{{ $reason->id }}" {{ old('exit_reason_id', $exit->exit_reason_id) == $reason->id ? 'selected' : '' }}>
                                {{ $reason->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('exit_reason_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> بروزرسانی
            </button>
        </form>
    </div>
</div>
@endsection
