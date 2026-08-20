@extends('behin-layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fa fa-person-check"></i> مدیریت تحویل گیرندگان</h4>
    <a href="{{ route('inventory.receivers.create') }}" class="btn btn-primary">
        <i class="fa fa-plus-lg"></i> تحویل گیرنده جدید
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ردیف</th>
                        <th>نام</th>
                        <th>کاربر متصل</th>
                        <th>تعداد تحویل</th>
                        <th>وضعیت</th>
                        <th>ثبت کننده</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($receivers as $receiver)
                        <tr class="{{ $receiver->is_active ? '' : 'table-secondary' }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $receiver->name }}</td>
                            <td>{{ $receiver->user->name ?? '-' }}</td>
                            <td>{{ $receiver->exits_count }}</td>
                            <td>
                                @if($receiver->is_active)
                                    <span class="badge bg-success">فعال</span>
                                @else
                                    <span class="badge bg-secondary">غیرفعال</span>
                                @endif
                            </td>
                            <td>{{ $receiver->creator->name }}</td>
                            <td class="text-nowrap">
                                <div class="d-flex gap-1">
                                    <a href="{{ route('inventory.receivers.show', $receiver) }}" class="btn btn-sm btn-info">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('inventory.receivers.edit', $receiver) }}" class="btn btn-sm btn-warning">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <form action="{{ route('inventory.receivers.toggle-active', $receiver) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm {{ $receiver->is_active ? 'btn-secondary' : 'btn-success' }}"
                                            title="{{ $receiver->is_active ? 'غیرفعال کردن' : 'فعال کردن' }}">
                                            <i class="fa {{ $receiver->is_active ? 'fa-pause-circle' : 'fa-play-circle' }}"></i>
                                        </button>
                                    </form>
                                    @if($receiver->exits_count === 0)
                                        <form action="{{ route('inventory.receivers.destroy', $receiver) }}" method="POST"
                                            onsubmit="return confirm('آیا از حذف این تحویل گیرنده اطمینان دارید؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">هیچ تحویل گیرنده‌ای ثبت نشده است.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
