@extends('layouts.admin')

@section('title', 'Danh sách tài khoản')

@section('header')
    <h1 class="h2">
        <i class="bi bi-people me-2"></i>
        Danh sách tài khoản
    </h1>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>
                    Danh sách tài khoản
                </h5>
            </div>
            <div class="col-auto">
                <small>Tổng: {{ $users->total() }} tài khoản</small>
            </div>
        </div>
    </div>

    <div class="card-body">
        <!-- Search and Filter Form -->
        <form method="GET" action="{{ route('users.index') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text"
                               class="form-control"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Tìm kiếm theo ID, username, email...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                        <option value="locked" {{ request('status') == 'locked' ? 'selected' : '' }}>Bị khóa</option>
                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Đã kích hoạt</option>
                        <option value="unverified" {{ request('status') == 'unverified' ? 'selected' : '' }}>Chưa kích hoạt</option>
                        <option value="admin" {{ request('status') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="bi bi-funnel me-1"></i>
                        Lọc
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        Reset
                    </a>
                </div>
            </div>
        </form>

        <!-- Users Table -->
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Trạng thái</th>
                            <th>Kích hoạt</th>
                            <th>Admin</th>
                            <th>Level</th>
                            <th>Money</th>
                            <th>Đã nạp</th>
                            <th>Ngày tạo</th>
                            <th width="200">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <strong>#{{ $user->id }}</strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="bi bi-person text-white"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $user->username }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($user->isLock)
                                        <span class="badge bg-danger">
                                            <i class="bi bi-lock me-1"></i>Bị khóa
                                        </span>
                                    @elseif($user->isLoad)
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>Hoạt động
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-pause-circle me-1"></i>Không hoạt động
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->verified)
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle-fill me-1"></i>Đã kích hoạt
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="bi bi-exclamation-circle me-1"></i>Chưa kích hoạt
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->isAdmin)
                                        <span class="badge bg-danger">
                                            <i class="bi bi-shield-check me-1"></i>Admin
                                        </span>
                                    @else
                                        <span class="badge bg-light text-dark">User</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark">
                                        Level {{ $user->level }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-success fw-bold">
                                        {{ number_format($user->money) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-info fw-bold">
                                        {{ number_format($user->danap) }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $user->time ? $user->time->format('d/m/Y H:i') : 'N/A' }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('users.show', $user) }}"
                                           class="btn btn-outline-info"
                                           title="Xem chi tiết">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('users.inventory', $user) }}"
                                           class="btn btn-outline-success"
                                           title="Xem túi đồ">
                                            <i class="bi bi-bag"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}"
                                           class="btn btn-outline-primary"
                                           title="Chỉnh sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST"
                                              action="{{ route('users.destroy', $user) }}"
                                              class="d-inline"
                                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-outline-danger"
                                                    title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-3 border-top">
                <div class="mb-2 mb-md-0">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Hiển thị <strong>{{ $users->firstItem() }}</strong> - <strong>{{ $users->lastItem() }}</strong>
                        trong tổng số <strong>{{ $users->total() }}</strong> kết quả
                    </small>
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-people display-1 text-muted"></i>
                <h4 class="mt-3 text-muted">Không tìm thấy tài khoản nào</h4>
                <p class="text-muted">
                    @if(request()->hasAny(['search', 'status']))
                        Thử thay đổi bộ lọc hoặc
                        <a href="{{ route('users.index') }}">xóa bộ lọc</a>
                    @else
                        Không có tài khoản nào trong hệ thống
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar-sm {
        width: 32px;
        height: 32px;
        font-size: 14px;
    }

    .table td {
        vertical-align: middle;
    }

    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
    }
</style>
@endpush
