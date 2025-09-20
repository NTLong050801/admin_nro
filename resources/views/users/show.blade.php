@extends('layouts.admin')

@section('title', 'Chi tiết tài khoản')

@section('header')
    <h1 class="h2">
        <i class="bi bi-person me-2"></i>
        Chi tiết tài khoản: {{ $user->username }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Quay lại danh sách
            </a>
            <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i>
                Chỉnh sửa
            </a>
        </div>
        <div class="btn-group">
            @if($user->ban)
                <form method="POST" action="{{ route('users.toggle-ban', $user) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success" title="Bỏ cấm">
                        <i class="bi bi-check-circle me-1"></i>
                        Bỏ cấm
                    </button>
                </form>
            @else
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#banModal">
                    <i class="bi bi-ban me-1"></i>
                    Cấm tài khoản
                </button>
            @endif
            
            <form method="POST" 
                  action="{{ route('users.destroy', $user) }}" 
                  class="d-inline"
                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản này? Hành động này không thể hoàn tác!')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash me-1"></i>
                    Xóa
                </button>
            </form>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- User Information -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Thông tin tài khoản
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">ID</label>
                        <div class="fw-bold">#{{ $user->id }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Username</label>
                        <div class="fw-bold">
                            {{ $user->username }}
                            @if($user->admin)
                                <span class="badge bg-danger ms-2">Admin</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Email</label>
                        <div class="fw-bold">{{ $user->email ?: 'Chưa có' }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Trạng thái</label>
                        <div>
                            @if($user->ban)
                                <span class="badge bg-danger fs-6">
                                    <i class="bi bi-ban me-1"></i>Bị cấm
                                </span>
                            @elseif($user->active)
                                <span class="badge bg-success fs-6">
                                    <i class="bi bi-check-circle me-1"></i>Hoạt động
                                </span>
                            @else
                                <span class="badge bg-secondary fs-6">
                                    <i class="bi bi-pause-circle me-1"></i>Không hoạt động
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Cấp VIP</label>
                        <div>
                            @if($user->vip > 0)
                                <span class="badge bg-warning text-dark fs-6">
                                    <i class="bi bi-star-fill me-1"></i>VIP {{ $user->vip }}
                                </span>
                            @else
                                <span class="badge bg-light text-dark fs-6">Thường</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">IP Address</label>
                        <div class="fw-bold">{{ $user->ip_address ?: 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Game Statistics -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>
                    Thống kê game
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                            <i class="bi bi-coin display-6 text-success"></i>
                            <h4 class="mt-2 mb-1 text-success">{{ number_format($user->goldbar) }}</h4>
                            <small class="text-muted">Goldbar</small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                            <i class="bi bi-cash-stack display-6 text-info"></i>
                            <h4 class="mt-2 mb-1 text-info">{{ number_format($user->tongnap) }}</h4>
                            <small class="text-muted">Tổng nạp</small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="text-center p-3 bg-warning bg-opacity-10 rounded">
                            <i class="bi bi-star display-6 text-warning"></i>
                            <h4 class="mt-2 mb-1 text-warning">{{ number_format($user->tichdiem) }}</h4>
                            <small class="text-muted">Tích điểm</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ban Information -->
        @if($user->ban)
            <div class="card mb-4 border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-ban me-2"></i>
                        Thông tin cấm
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Ngày cấm đến</label>
                            <div class="fw-bold text-danger">
                                {{ $user->ban_until ? $user->ban_until->format('d/m/Y H:i') : 'Vĩnh viễn' }}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted">Lý do cấm</label>
                            <div class="fw-bold">{{ $user->reason ?: 'Không có lý do cụ thể' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Activity Timeline -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>
                    Hoạt động gần đây
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @if($user->last_time_login)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Đăng nhập lần cuối</h6>
                                <small class="text-muted">
                                    {{ $user->last_time_login->format('d/m/Y H:i') }}
                                    <br>
                                    ({{ $user->last_time_login->diffForHumans() }})
                                </small>
                            </div>
                        </div>
                    @endif

                    @if($user->last_time_logout)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Đăng xuất lần cuối</h6>
                                <small class="text-muted">
                                    {{ $user->last_time_logout->format('d/m/Y H:i') }}
                                    <br>
                                    ({{ $user->last_time_logout->diffForHumans() }})
                                </small>
                            </div>
                        </div>
                    @endif

                    @if($user->created_time)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Tạo tài khoản</h6>
                                <small class="text-muted">
                                    {{ $user->created_time->format('d/m/Y H:i') }}
                                    <br>
                                    ({{ $user->created_time->diffForHumans() }})
                                </small>
                            </div>
                        </div>
                    @endif
                </div>

                @if(!$user->last_time_login && !$user->last_time_logout && !$user->created_time)
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-clock display-4"></i>
                        <p class="mt-2">Chưa có hoạt động nào</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Ban Modal -->
<div class="modal fade" id="banModal" tabindex="-1" aria-labelledby="banModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="banModalLabel">
                    <i class="bi bi-ban me-2"></i>
                    Cấm tài khoản: {{ $user->username }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('users.toggle-ban', $user) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="ban_reason" class="form-label">Lý do cấm</label>
                        <textarea class="form-control" 
                                  id="ban_reason" 
                                  name="reason" 
                                  rows="4" 
                                  placeholder="Nhập lý do cấm tài khoản..." 
                                  required></textarea>
                    </div>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Tài khoản sẽ bị cấm trong 30 ngày. Bạn có thể bỏ cấm bất cứ lúc nào.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-ban me-1"></i>
                        Cấm tài khoản
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    
    .timeline-marker {
        position: absolute;
        left: -23px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #e9ecef;
    }
    
    .timeline-content h6 {
        margin-bottom: 5px;
        font-weight: 600;
    }
    
    .badge.fs-6 {
        font-size: 0.875rem !important;
    }
</style>
@endpush
