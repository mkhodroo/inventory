@extends('behin-layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-list-check"></i> مدیریت دلایل تسویه</h4>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">افزودن دلیل جدید</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('inventory.settlement-reasons.store') }}" method="POST">
            @csrf
            <div class="input-group">
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                    name="name" placeholder="نام دلیل تسویه (مثلا فروش یا عودت)" required>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> افزودن
                </button>
            </div>
            @error('name')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
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
                        <th>نام دلیل</th>
                        <th>ثبت کننده</th>
                        <th>تعداد استفاده</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($settlementReasons as $reason)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $reason->name }}</td>
                            <td>{{ $reason->creator->name }}</td>
                            <td>{{ $reason->settlements()->count() }}</td>
                            <td class="text-nowrap">
                                @if($reason->settlements()->count() === 0)
                                    <div class="d-flex gap-1">
                                        <form action="{{ route('inventory.settlement-reasons.update', $reason) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" name="name" value="{{ $reason->name }}" required>
                                                <button type="submit" class="btn btn-warning btn-sm">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </div>
                                        </form>
                                        <form action="{{ route('inventory.settlement-reasons.destroy', $reason) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('آیا از حذف این دلیل اطمینان دارید؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted">در حال استفاده</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">هیچ دلیلی ثبت نشده است.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
