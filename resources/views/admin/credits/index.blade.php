@extends('layouts.app')

@section('content')
<div class="container" style="direction: rtl; text-align: right;">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">إدارة أرصدة المستخدمين</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الاسم</th>
                                <th>البريد الإلكتروني</th>
                                <th>الرصيد الحالي</th>
                                <th>تحديث الرصيد</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ number_format($user->credit, 2) }} جنيه</td>
                                    <td>
                                        <form action="{{ route('admin.credits.update', $user) }}" method="POST" class="d-flex">
                                            @csrf
                                            <input type="number" name="credit" class="form-control me-2" step="0.01" value="{{ $user->credit }}" required>
                                            <button type="submit" class="btn btn-primary">تحديث</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
