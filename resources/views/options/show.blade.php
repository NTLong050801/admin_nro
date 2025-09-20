@extends('layouts.admin')

@section('title', 'Chi tiết nhân vật')

@section('header')
    <h1 class="h2">
        <i class="bi bi-eye me-2"></i>
        Chi tiết nhân vật: {{ $option->cName }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('options.index') }}" class="btn btn-outline-secondary me-2">
            <i class="bi bi-arrow-left me-1"></i>
            Quay lại danh sách
        </a>
        <a href="{{ route('options.edit', $option->playerId) }}" class="btn btn-outline-warning">
            <i class="bi bi-pencil me-1"></i>
            Chỉnh sửa
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Character Info -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person me-2"></i>
                    Thông tin nhân vật
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>ID:</strong></td>
                                <td>#{{ $option->playerId }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tên:</strong></td>
                                <td>{{ $option->cName }}</td>
                            </tr>
                            <tr>
                                <td><strong>Giới tính:</strong></td>
                                <td>{{ $option->gender_text }}</td>
                            </tr>
                            <tr>
                                <td><strong>Hành tinh:</strong></td>
                                <td>
                                    @php
                                        $classColors = ['success', 'info', 'warning'];
                                        $classColor = $classColors[$option->nClassId] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $classColor }}">{{ $option->class_text }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Đầu:</strong></td>
                                <td>{{ $option->head }}</td>
                            </tr>
                            <tr>
                                <td><strong>Trạng thái:</strong></td>
                                <td>
                                    @if($option->isCan)
                                        <span class="badge bg-success">Có thể hoạt động</span>
                                    @else
                                        <span class="badge bg-danger">Không thể hoạt động</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Sức mạnh:</strong></td>
                                <td><span class="text-danger fw-bold">{{ $option->formatted_power }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Giới hạn sức mạnh:</strong></td>
                                <td>{{ number_format($option->cPowerLimit) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tiềm năng:</strong></td>
                                <td>{{ number_format($option->cTiemNang) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Xu:</strong></td>
                                <td><span class="text-warning fw-bold">{{ $option->formatted_xu }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Lượng:</strong></td>
                                <td><span class="text-info fw-bold">{{ $option->formatted_luong }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Lượng khóa:</strong></td>
                                <td>{{ number_format($option->luongKhoa) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-bar-chart me-2"></i>
                    Chỉ số cơ bản
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">HP</label>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-success" 
                                     role="progressbar" 
                                     style="width: {{ ($option->cHP / max($option->cHPGoc, 1)) * 100 }}%">
                                    {{ number_format($option->cHP) }} / {{ number_format($option->cHPGoc) }}
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">MP</label>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-info" 
                                     role="progressbar" 
                                     style="width: {{ ($option->cMP / max($option->cMPGoc, 1)) * 100 }}%">
                                    {{ number_format($option->cMP) }} / {{ number_format($option->cMPGoc) }}
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Stamina</label>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-warning" 
                                     role="progressbar" 
                                     style="width: {{ ($option->cStamina / max($option->cMaxStamina, 1)) * 100 }}%">
                                    {{ $option->cStamina }} / {{ $option->cMaxStamina }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Sát thương gốc:</strong></td>
                                <td>{{ number_format($option->cDamGoc) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Phòng thủ gốc:</strong></td>
                                <td>{{ number_format($option->cDefGoc) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Chí mạng gốc:</strong></td>
                                <td>{{ number_format($option->cCriticalGoc) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Location & Other Info -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-geo-alt me-2"></i>
                    Vị trí và thông tin khác
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Map ID:</strong></td>
                                <td>{{ $option->mapTemplateId }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tọa độ:</strong></td>
                                <td>({{ $option->cx }}, {{ $option->cy }})</td>
                            </tr>
                            <tr>
                                <td><strong>Clan ID:</strong></td>
                                <td>{{ $option->clanId == -1 ? 'Không có' : $option->clanId }}</td>
                            </tr>
                            <tr>
                                <td><strong>Clan Point:</strong></td>
                                <td>{{ number_format($option->clanPoint) }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Point Event:</strong></td>
                                <td>{{ number_format($option->pointEvent) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Point VIP:</strong></td>
                                <td>{{ number_format($option->pointVip) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Point Event VIP:</strong></td>
                                <td>{{ number_format($option->pointEventVIP) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Total Gold:</strong></td>
                                <td>{{ number_format($option->totalGold) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Account Info & Actions -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person-circle me-2"></i>
                    Thông tin tài khoản
                </h5>
            </div>
            <div class="card-body">
                @if($option->user)
                    <div class="text-center mb-3">
                        <div class="avatar-lg bg-primary rounded-circle d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-person text-white" style="font-size: 2rem;"></i>
                        </div>
                        <h5 class="mt-2">{{ $option->user->username }}</h5>
                        @if($option->user->isAdmin)
                            <span class="badge bg-danger">Admin</span>
                        @endif
                    </div>
                    
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>{{ $option->user->email ?: 'Chưa có' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Level:</strong></td>
                            <td><span class="badge bg-info">{{ $option->user->level }}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Money:</strong></td>
                            <td>{{ number_format($option->user->money) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Đã nạp:</strong></td>
                            <td>{{ number_format($option->user->danap) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Trạng thái:</strong></td>
                            <td>
                                @if($option->user->isLock)
                                    <span class="badge bg-danger">Bị khóa</span>
                                @elseif($option->user->isLoad)
                                    <span class="badge bg-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-secondary">Không hoạt động</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Kích hoạt:</strong></td>
                            <td>
                                @if($option->user->verified)
                                    <span class="badge bg-success">Đã kích hoạt</span>
                                @else
                                    <span class="badge bg-warning">Chưa kích hoạt</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('users.show', $option->user->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye me-1"></i>
                            Xem tài khoản
                        </a>
                        <a href="{{ route('users.edit', $option->user->id) }}" class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-pencil me-1"></i>
                            Sửa tài khoản
                        </a>
                    </div>
                @else
                    <div class="text-center text-muted">
                        <i class="bi bi-exclamation-triangle display-4"></i>
                        <p class="mt-2">Không tìm thấy thông tin tài khoản</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-lightning me-2"></i>
                    Thao tác nhanh
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('options.edit', $option->playerId) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-1"></i>
                        Chỉnh sửa nhân vật
                    </a>
                    
                    <form method="POST" action="{{ route('options.reset-stats', $option->playerId) }}" class="d-inline">
                        @csrf
                        <button type="submit" 
                                class="btn btn-outline-danger w-100"
                                onclick="return confirm('Bạn có chắc muốn reset chỉ số về mặc định?')">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            Reset chỉ số
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar-lg {
        width: 80px;
        height: 80px;
    }
</style>
@endpush
