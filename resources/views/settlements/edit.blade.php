@extends('behin-layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-pencil"></i> ویرایش تسویه کالا</h4>
    <a href="{{ route('inventory.settlements.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right"></i> بازگشت
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('inventory.settlements.update', $settlement) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="receiver_id" class="form-label">تحویل دهنده (فردی که کالا را برمی‌گرداند)</label>
                    <select class="form-select @error('receiver_id') is-invalid @enderror"
                        id="receiver_id" name="receiver_id" required>
                        <option value="">-- انتخاب تحویل گیرنده --</option>
                        @foreach($receivers as $receiver)
                            <option value="{{ $receiver->id }}" {{ old('receiver_id', $settlement->receiver_id) == $receiver->id ? 'selected' : '' }}>
                                {{ $receiver->name }}
                            </option>
                        @endforeach
                        @if($settlement->receiver && ! $settlement->receiver->is_active)
                            <option value="{{ $settlement->receiver_id }}" selected>
                                {{ $settlement->receiver->name }} (غیرفعال)
                            </option>
                        @endif
                    </select>
                    @error('receiver_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="product_id" class="form-label">محصول</label>
                    <select class="form-select @error('product_id') is-invalid @enderror"
                        id="product_id" name="product_id" required>
                        <option value="">-- انتخاب محصول --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id', $settlement->product_id) == $product->id ? 'selected' : '' }}>
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
                        id="quantity" name="quantity" value="{{ old('quantity', $settlement->quantity) }}" min="1" required>
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="settlement_reason_id" class="form-label">دلیل تسویه</label>
                    <select class="form-select @error('settlement_reason_id') is-invalid @enderror"
                        id="settlement_reason_id" name="settlement_reason_id" required>
                        <option value="">-- انتخاب دلیل --</option>
                        @foreach($settlementReasons as $reason)
                            <option value="{{ $reason->id }}" {{ old('settlement_reason_id', $settlement->settlement_reason_id) == $reason->id ? 'selected' : '' }}>
                                {{ $reason->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('settlement_reason_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="document" class="form-label">سند تسویه</label>
                    @if($settlement->document)
                        <a href="{{ route('inventory.settlements.download-document', $settlement) }}" class="d-block mb-2">
                            <i class="bi bi-paperclip"></i> سند فعلی
                        </a>
                    @endif
                    <input type="file" class="form-control @error('document') is-invalid @enderror"
                        id="document" name="document" accept=".pdf,.jpg,.jpeg,.png">
                    @error('document')
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
