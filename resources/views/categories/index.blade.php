@extends('behin-layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fa fa-tags"></i> مدیریت دسته‌بندی‌ها</h4>
    <a href="{{ route('inventory.categories.create') }}" class="btn btn-primary">
        <i class="fa fa-plus-lg"></i> ایجاد دسته‌بندی جدید
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ردیف</th>
                        <th>نام دسته‌بندی</th>
                        <th>کد</th>
                        <th>کد اصلی</th>
                        <th>دسته‌بندی والد</th>
                        <th>تعداد محصولات</th>
                        <th>ثبت کننده</th>
                        <th>ویرایش کنندگان</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->code }}</td>
                            <td>{{ $category->main_code }}</td>
                            <td>{{ $category->parent?->name ?? '-' }}</td>
                            <td>{{ $category->products_count }}</td>
                            <td>{{ $category->creator->name }}</td>
                            <td>
                                @foreach($category->editors as $editor)
                                    <span class="badge bg-secondary">{{ $editor->name }}</span>
                                @endforeach
                            </td>
                            <td class="text-nowrap">
                                <div class="d-flex gap-1">
                                    <a href="{{ route('inventory.categories.show', $category) }}" class="btn btn-sm btn-info">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('inventory.categories.edit', $category) }}" class="btn btn-sm btn-warning">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <form action="{{ route('inventory.categories.destroy', $category) }}" method="POST"
                                        onsubmit="return confirm('آیا از حذف این دسته‌بندی اطمینان دارید؟')">
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
                            <td colspan="9" class="text-center">هیچ دسته‌بندی‌ای یافت نشد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
