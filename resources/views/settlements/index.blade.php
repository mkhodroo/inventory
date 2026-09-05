@extends('behin-layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fa fa-journal-check"></i> تسویه کالا</h4>
    <a href="{{ route('inventory.settlements.create') }}" class="btn btn-primary">
        <i class="fa fa-plus-lg"></i> ثبت تسویه جدید
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">فیلتر بر اساس تحویل گیرنده</label>
                <select name="receiver_id" class="form-select select2">
                    <option value="">همه تحویل گیرندگان</option>
                    @foreach($receivers as $receiver)
                        <option value="{{ $receiver->id }}" {{ request('receiver_id') == $receiver->id ? 'selected' : '' }}>
                            {{ $receiver->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">فیلتر بر اساس محصول</label>
                <select name="product_id" class="form-select select2">
                    <option value="">همه محصولات</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} | {{ $product->main_code }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-secondary me-2">
                    <i class="fa fa-funnel"></i> فیلتر
                </button>
                <a href="{{ route('inventory.settlements.index') }}" class="btn btn-outline-secondary">
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
                        <th>تحویل گیرنده</th>
                        <th>محصول</th>
                        <th>تعداد</th>
                        <th>دلیل تسویه</th>
                        <th>سند</th>
                        <th>ثبت کننده</th>
                        <th>تاریخ</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($settlements as $settlement)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $settlement->receiver->name }}</td>
                            <td>{{ $settlement->product->name }}</td>
                            <td><span class="badge bg-success">{{ $settlement->quantity }}</span></td>
                            <td>{{ $settlement->settlementReason->name }}</td>
                            <td>
                                @if($settlement->document)
                                    <a href="{{ route('inventory.settlements.download-document', $settlement) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fa fa-paperclip"></i> دانلود
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $settlement->creator->name }}</td>
                            <td>{{ $settlement->created_at->format('Y/m/d H:i') }}</td>
                            <td class="text-nowrap">
                                <div class="d-flex gap-1">
                                    <a href="{{ route('inventory.settlements.edit', $settlement) }}" class="btn btn-sm btn-warning">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <form action="{{ route('inventory.settlements.destroy', $settlement) }}" method="POST"
                                        onsubmit="return confirm('آیا از حذف این تسویه اطمینان دارید؟')">
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
                            <td colspan="9" class="text-center">هیچ تسویه‌ای ثبت نشده است.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
