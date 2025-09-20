@extends('layouts.admin')

@section('title', 'Dashboard')

@section('header')
    <h1 class="h2">
        <i class="bi bi-speedometer2 me-2"></i>
        Dashboard
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <small class="text-muted">Chào mừng bạn đến với hệ thống quản lý</small>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Tổng tài khoản
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ \App\Models\GameUser::count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people display-4 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Tài khoản hoạt động
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ \App\Models\GameUser::where('isLoad', true)->where('isLock', false)->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-check-circle display-4 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Tài khoản Admin
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ \App\Models\GameUser::where('isAdmin', true)->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-shield-check display-4 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Tài khoản bị khóa
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ \App\Models\GameUser::where('isLock', true)->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-lock display-4 text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Users -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>
                    Tài khoản đăng nhập gần đây
                </h5>
            </div>
            <div class="card-body">
                @php
                    $recentUsers = \App\Models\GameUser::whereNotNull('lastlogout')
                        ->where('lastlogout', '>', 0)
                        ->orderBy('lastlogout', 'desc')
                        ->limit(10)
                        ->get();
                @endphp

                @if($recentUsers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Trạng thái</th>
                                    <th>Admin</th>
                                    <th>Level</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentUsers as $user)
                                    <tr>
                                        <td>
                                            <strong>{{ $user->username }}</strong>
                                        </td>
                                        <td>
                                            @if($user->isLock)
                                                <span class="badge bg-danger">Bị khóa</span>
                                            @elseif($user->isLoad)
                                                <span class="badge bg-success">Hoạt động</span>
                                            @else
                                                <span class="badge bg-secondary">Không hoạt động</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->isAdmin)
                                                <span class="badge bg-danger">Admin</span>
                                            @else
                                                <span class="badge bg-light text-dark">User</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info text-dark">Level {{ $user->level }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-clock display-4 text-muted"></i>
                        <p class="mt-2 text-muted">Chưa có dữ liệu đăng nhập</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-lightning me-2"></i>
                    Thao tác nhanh
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('users.index') }}" class="btn btn-primary">
                        <i class="bi bi-people me-2"></i>
                        Quản lý tài khoản
                    </a>
                    <a href="{{ route('users.index', ['status' => 'locked']) }}" class="btn btn-warning">
                        <i class="bi bi-lock me-2"></i>
                        Xem tài khoản bị khóa
                    </a>
                    <a href="{{ route('users.index', ['status' => 'admin']) }}" class="btn btn-info">
                        <i class="bi bi-shield-check me-2"></i>
                        Xem tài khoản Admin
                    </a>
                </div>
            </div>
        </div>

        <!-- System Info -->
        <div class="card shadow mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Thông tin hệ thống
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-2">
                        <small class="text-muted">Database:</small>
                        <div class="fw-bold">{{ config('database.connections.mysql.database') }}</div>
                    </div>
                    <div class="col-12 mb-2">
                        <small class="text-muted">Laravel Version:</small>
                        <div class="fw-bold">{{ app()->version() }}</div>
                    </div>
                    <div class="col-12">
                        <small class="text-muted">PHP Version:</small>
                        <div class="fw-bold">{{ PHP_VERSION }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .border-left-primary {
        border-left: 0.25rem solid #667eea !important;
    }

    .border-left-success {
        border-left: 0.25rem solid #28a745 !important;
    }

    .border-left-warning {
        border-left: 0.25rem solid #ffc107 !important;
    }

    .border-left-danger {
        border-left: 0.25rem solid #dc3545 !important;
    }

    .text-xs {
        font-size: 0.75rem;
    }

    .font-weight-bold {
        font-weight: 700 !important;
    }

    .text-gray-800 {
        color: #5a5c69 !important;
    }

    .no-gutters {
        margin-right: 0;
        margin-left: 0;
    }

    .no-gutters > .col,
    .no-gutters > [class*="col-"] {
        padding-right: 0;
        padding-left: 0;
    }
</style>
@endpush
