@extends('layouts.admin')

@section('title', 'Quản lý nhân vật')

@section('header')
    <h1 class="h2">
        <i class="bi bi-person-gear me-2"></i>
        Quản lý nhân vật
    </h1>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-funnel me-2"></i>
            Bộ lọc và tìm kiếm
        </h5>
    </div>
    <div class="card-body">
        <!-- Search and Filter Form -->
        <form method="GET" action="{{ route('options.index') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text"
                               class="form-control"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Tìm kiếm theo tên nhân vật, Player ID...">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="class" class="form-select">
                        <option value="">Tất cả hành tinh</option>
                        <option value="0" {{ request('class') == '0' ? 'selected' : '' }}>Trái Đất</option>
                        <option value="1" {{ request('class') == '1' ? 'selected' : '' }}>Namek</option>
                        <option value="2" {{ request('class') == '2' ? 'selected' : '' }}>Xayda</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="gender" class="form-select">
                        <option value="">Tất cả giới tính</option>
                        <option value="0" {{ request('gender') == '0' ? 'selected' : '' }}>Nam</option>
                        <option value="1" {{ request('gender') == '1' ? 'selected' : '' }}>Nữ</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number"
                           class="form-control"
                           name="power_min"
                           value="{{ request('power_min') }}"
                           placeholder="Sức mạnh tối thiểu">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="bi bi-funnel me-1"></i>
                        Lọc
                    </button>
                </div>
                <div class="col-md-1">
                    <a href="{{ route('options.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        Reset
                    </a>
                </div>
            </div>
        </form>

        <!-- Sort Options -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="btn-group" role="group">
                    <a href="{{ route('options.index', array_merge(request()->query(), ['sort' => 'cPower', 'direction' => 'desc'])) }}"
                       class="btn btn-sm {{ request('sort') == 'cPower' ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="bi bi-lightning me-1"></i>Sức mạnh
                    </a>
                    <a href="{{ route('options.index', array_merge(request()->query(), ['sort' => 'xu', 'direction' => 'desc'])) }}"
                       class="btn btn-sm {{ request('sort') == 'xu' ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="bi bi-coin me-1"></i>Xu
                    </a>
                    <a href="{{ route('options.index', array_merge(request()->query(), ['sort' => 'luong', 'direction' => 'desc'])) }}"
                       class="btn btn-sm {{ request('sort') == 'luong' ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="bi bi-gem me-1"></i>Lượng
                    </a>
                    <a href="{{ route('options.index', array_merge(request()->query(), ['sort' => 'lastTime', 'direction' => 'desc'])) }}"
                       class="btn btn-sm {{ request('sort') == 'lastTime' ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="bi bi-clock me-1"></i>Hoạt động
                    </a>
                </div>
            </div>
        </div>

        <!-- Characters Table -->
        @if($options->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nhân vật</th>
                            <th>Hành tinh</th>
                            <th>Sức mạnh</th>
                            <th>Xu</th>
                            <th>Lượng</th>
                            <th>Map</th>
                            <th width="200">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($options as $option)
                            <tr>
                                <td>
                                    <strong>#{{ $option->playerId }}</strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="bi bi-person text-white"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $option->cName }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $classColors = ['success', 'info', 'warning'];
                                        $classColor = $classColors[$option->nClassId] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $classColor }}">
                                        {{ $option->class_text }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-danger fw-bold">
                                        {{ $option->formatted_power }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-warning fw-bold">
                                        {{ $option->formatted_xu }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-info fw-bold">
                                        {{ $option->formatted_luong }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        Map: {{ $option->mapTemplateId }}<br>
                                        ({{ $option->cx }}, {{ $option->cy }})
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('options.show', $option->playerId) }}"
                                           class="btn btn-outline-info"
                                           title="Xem chi tiết">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('options.edit', $option->playerId) }}"
                                           class="btn btn-outline-warning"
                                           title="Chỉnh sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
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
                        Hiển thị <strong>{{ $options->firstItem() }}</strong> - <strong>{{ $options->lastItem() }}</strong>
                        trong tổng số <strong>{{ $options->total() }}</strong> nhân vật
                    </small>
                </div>
                <div>
                    {{ $options->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-person-gear display-1 text-muted"></i>
                <h4 class="mt-3 text-muted">Không tìm thấy nhân vật nào</h4>
                <p class="text-muted">
                    @if(request()->hasAny(['search', 'class', 'gender', 'power_min']))
                        Thử thay đổi bộ lọc hoặc
                        <a href="{{ route('options.index') }}">xóa bộ lọc</a>
                    @else
                        Không có nhân vật nào trong hệ thống
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
@endsection


