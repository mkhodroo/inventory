@extends('behin-layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fa fa-building"></i> مدیریت انبارها</h4>
    <a href="{{ route('inventory.warehouses.create') }}" class="btn btn-primary">
        <i class="fa fa-plus-lg"></i> ایجاد انبار جدید
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ردیف</th>
                        <th>نام انبار</th>
                        <th>آدرس</th>
                        <th>تلفن</th>
                        <th>ثبت کننده</th>
                        <th>تعداد ورود</th>
                        <th>تعداد خروج</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($warehouses as $warehouse)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $warehouse->name }}</td>
                            <td>{{ $warehouse->address }}</td>
                            <td>{{ $warehouse->phone }}</td>
                            <td>{{ $warehouse->creator->name }}</td>
                            <td>{{ $warehouse->entries_count }}</td>
                            <td>{{ $warehouse->exits_count }}</td>
                            <td class="text-nowrap">
                                <div class="d-flex gap-1">
                                    <a href="{{ route('inventory.warehouses.show', $warehouse) }}" class="btn btn-sm btn-info">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('inventory.warehouses.edit', $warehouse) }}" class="btn btn-sm btn-warning">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <form action="{{ route('inventory.warehouses.destroy', $warehouse) }}" method="POST"
                                        onsubmit="return confirm('آیا از حذف این انبار اطمینان دارید؟')">
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
                            <td colspan="8" class="text-center">هیچ انباری یافت نشد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
