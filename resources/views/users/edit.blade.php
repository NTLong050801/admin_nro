@extends('layouts.admin')

@section('title', 'Chỉnh sửa tài khoản')

@section('header')
    <h1 class="h2">
        <i class="bi bi-pencil me-2"></i>
        Chỉnh sửa tài khoản: {{ $user->username }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary me-2">
            <i class="bi bi-arrow-left me-1"></i>
            Quay lại danh sách
        </a>
        <a href="{{ route('users.show', $user) }}" class="btn btn-outline-info">
            <i class="bi bi-eye me-1"></i>
            Xem chi tiết
        </a>
    </div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-pencil me-2"></i>
                    Chỉnh sửa thông tin tài khoản
                </h5>
            </div>
            
            <div class="card-body">
                <form method="POST" action="{{ route('users.update', $user) }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="bi bi-info-circle me-1"></i>
                                Thông tin cơ bản
                            </h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label">
                                Username <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" 
                                       class="form-control @error('username') is-invalid @enderror" 
                                       id="username" 
                                       name="username" 
                                       value="{{ old('username', $user->username) }}" 
                                       required>
                            </div>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">
                                Email <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}" 
                                       required>
                            </div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">
                                Mật khẩu mới
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password">
                            </div>
                            <small class="text-muted">Để trống nếu không muốn thay đổi mật khẩu</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">
                                Xác nhận mật khẩu mới
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Account Settings -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="bi bi-gear me-1"></i>
                                Cài đặt tài khoản
                            </h6>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="vip" class="form-label">Cấp VIP</label>
                            <select class="form-select @error('vip') is-invalid @enderror" 
                                    id="vip" 
                                    name="vip">
                                <option value="0" {{ old('vip', $user->vip) == '0' ? 'selected' : '' }}>Thường</option>
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ old('vip', $user->vip) == $i ? 'selected' : '' }}>
                                        VIP {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            @error('vip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="goldbar" class="form-label">Goldbar</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-coin"></i>
                                </span>
                                <input type="number" 
                                       class="form-control @error('goldbar') is-invalid @enderror" 
                                       id="goldbar" 
                                       name="goldbar" 
                                       value="{{ old('goldbar', $user->goldbar) }}" 
                                       min="0">
                            </div>
                            @error('goldbar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="tongnap" class="form-label">Tổng nạp</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-cash-stack"></i>
                                </span>
                                <input type="number" 
                                       class="form-control @error('tongnap') is-invalid @enderror" 
                                       id="tongnap" 
                                       name="tongnap" 
                                       value="{{ old('tongnap', $user->tongnap) }}" 
                                       min="0">
                            </div>
                            @error('tongnap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="tichdiem" class="form-label">Tích điểm</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-star"></i>
                                </span>
                                <input type="number" 
                                       class="form-control @error('tichdiem') is-invalid @enderror" 
                                       id="tichdiem" 
                                       name="tichdiem" 
                                       value="{{ old('tichdiem', $user->tichdiem) }}" 
                                       min="0">
                            </div>
                            @error('tichdiem')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Status & Permissions -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="bi bi-shield-check me-1"></i>
                                Trạng thái & Quyền hạn
                            </h6>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="active" 
                                       name="active" 
                                       value="1" 
                                       {{ old('active', $user->active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="active">
                                    <i class="bi bi-check-circle text-success me-1"></i>
                                    Kích hoạt tài khoản
                                </label>
                            </div>
                            <small class="text-muted">Tài khoản có thể đăng nhập và sử dụng</small>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="admin" 
                                       name="admin" 
                                       value="1" 
                                       {{ old('admin', $user->admin) ? 'checked' : '' }}>
                                <label class="form-check-label" for="admin">
                                    <i class="bi bi-shield-fill text-danger me-1"></i>
                                    Quyền quản trị
                                </label>
                            </div>
                            <small class="text-muted">Cấp quyền admin cho tài khoản</small>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="ban" 
                                       name="ban" 
                                       value="1" 
                                       {{ old('ban', $user->ban) ? 'checked' : '' }}>
                                <label class="form-check-label" for="ban">
                                    <i class="bi bi-ban text-danger me-1"></i>
                                    Cấm tài khoản
                                </label>
                            </div>
                            <small class="text-muted">Cấm tài khoản không được sử dụng</small>
                        </div>
                        
                        @if($user->ban)
                            <div class="col-12 mb-3" id="ban-reason-section">
                                <label for="reason" class="form-label">Lý do cấm</label>
                                <textarea class="form-control @error('reason') is-invalid @enderror" 
                                          id="reason" 
                                          name="reason" 
                                          rows="3" 
                                          placeholder="Nhập lý do cấm tài khoản...">{{ old('reason', $user->reason) }}</textarea>
                                @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    Hủy bỏ
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i>
                                    Cập nhật tài khoản
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }
    
    .input-group-text {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
    
    .card-header {
        border-bottom: 2px solid #e9ecef;
    }
    
    .border-bottom {
        border-color: #e9ecef !important;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const banCheckbox = document.getElementById('ban');
        const banReasonSection = document.getElementById('ban-reason-section');
        
        // Show/hide ban reason section based on ban checkbox
        function toggleBanReason() {
            if (banCheckbox.checked) {
                if (!banReasonSection) {
                    // Create ban reason section if it doesn't exist
                    const reasonHtml = `
                        <div class="col-12 mb-3" id="ban-reason-section">
                            <label for="reason" class="form-label">Lý do cấm</label>
                            <textarea class="form-control" 
                                      id="reason" 
                                      name="reason" 
                                      rows="3" 
                                      placeholder="Nhập lý do cấm tài khoản..."></textarea>
                        </div>
                    `;
                    banCheckbox.closest('.row').insertAdjacentHTML('beforeend', reasonHtml);
                }
            } else {
                if (banReasonSection) {
                    banReasonSection.style.display = 'none';
                }
            }
        }
        
        banCheckbox.addEventListener('change', toggleBanReason);
        toggleBanReason(); // Initial check
    });
</script>
@endpush
