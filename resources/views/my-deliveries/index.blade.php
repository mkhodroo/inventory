@extends('behin-layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fa fa-box2-heart"></i> تحویل‌های من</h4>
</div>

@if($receivers->isEmpty())
    <div class="alert alert-warning">
        هیچ تحویل گیرنده‌ای به حساب کاربری شما متصل نیست. در صورت نیاز با ادمین هماهنگ کنید.
    </div>
@else
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">تحویل گیرندگان متصل به حساب شما</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ردیف</th>
                            <th>نام تحویل گیرنده</th>
                            <th>تعداد تحویل</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($receivers as $receiver)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $receiver->name }}</td>
                                <td>{{ $receiver->exits()->count() }}</td>
                                <td>
                                    <a href="{{ route('inventory.my-deliveries.show', $receiver) }}" class="btn btn-sm btn-info">
                                        <i class="fa fa-eye"></i> جزئیات
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">تحویل گیرنده‌ای ثبت نشده است.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
@endsection
