@extends('layouts.admin')

@section('title', 'Chỉnh sửa nhân vật')

@section('header')
    <h1 class="h2">
        <i class="bi bi-pencil me-2"></i>
        Chỉnh sửa nhân vật: {{ $option->cName }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('options.index') }}" class="btn btn-outline-secondary me-2">
            <i class="bi bi-arrow-left me-1"></i>
            Quay lại danh sách
        </a>
        <a href="{{ route('options.show', $option->playerId) }}" class="btn btn-outline-info">
            <i class="bi bi-eye me-1"></i>
            Xem chi tiết
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('options.update', $option->playerId) }}">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-person me-2"></i>
                        Thông tin cơ bản
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cName" class="form-label">Tên nhân vật</label>
                                <input type="text"
                                       class="form-control @error('cName') is-invalid @enderror"
                                       id="cName"
                                       name="cName"
                                       value="{{ old('cName', $option->cName) }}"
                                       maxlength="30"
                                       required>
                                @error('cName')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="cgender" class="form-label">Giới tính</label>
                                <select class="form-select @error('cgender') is-invalid @enderror"
                                        id="cgender"
                                        name="cgender"
                                        required>
                                    <option value="0" {{ old('cgender', $option->cgender) == 0 ? 'selected' : '' }}>Nam</option>
                                    <option value="1" {{ old('cgender', $option->cgender) == 1 ? 'selected' : '' }}>Nữ</option>
                                </select>
                                @error('cgender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="nClassId" class="form-label">Hành tinh</label>
                                <select class="form-select @error('nClassId') is-invalid @enderror"
                                        id="nClassId"
                                        name="nClassId"
                                        required>
                                    <option value="0" {{ old('nClassId', $option->nClassId) == 0 ? 'selected' : '' }}>Trái Đất</option>
                                    <option value="1" {{ old('nClassId', $option->nClassId) == 1 ? 'selected' : '' }}>Namek</option>
                                    <option value="2" {{ old('nClassId', $option->nClassId) == 2 ? 'selected' : '' }}>Xayda</option>
                                </select>
                                @error('nClassId')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="head" class="form-label">Đầu</label>
                                <input type="number"
                                       class="form-control @error('head') is-invalid @enderror"
                                       id="head"
                                       name="head"
                                       value="{{ old('head', $option->head) }}"
                                       min="0"
                                       required>
                                @error('head')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 form-check">
                                <input type="checkbox"
                                       class="form-check-input"
                                       id="isCan"
                                       name="isCan"
                                       value="1"
                                       {{ old('isCan', $option->isCan) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isCan">
                                    Có thể hoạt động
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-coin me-2"></i>
                        Tài sản
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="xu" class="form-label">Xu</label>
                                <input type="number"
                                       class="form-control @error('xu') is-invalid @enderror"
                                       id="xu"
                                       name="xu"
                                       value="{{ old('xu', $option->xu) }}"
                                       min="0"
                                       required>
                                @error('xu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="luong" class="form-label">Lượng</label>
                                <input type="number"
                                       class="form-control @error('luong') is-invalid @enderror"
                                       id="luong"
                                       name="luong"
                                       value="{{ old('luong', $option->luong) }}"
                                       min="0"
                                       required>
                                @error('luong')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="luongKhoa" class="form-label">Lượng khóa</label>
                                <input type="number"
                                       class="form-control @error('luongKhoa') is-invalid @enderror"
                                       id="luongKhoa"
                                       name="luongKhoa"
                                       value="{{ old('luongKhoa', $option->luongKhoa) }}"
                                       min="0"
                                       required>
                                @error('luongKhoa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning me-2"></i>
                        Chỉ số sức mạnh
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cPower" class="form-label">Sức mạnh</label>
                                <input type="number"
                                       class="form-control @error('cPower') is-invalid @enderror"
                                       id="cPower"
                                       name="cPower"
                                       value="{{ old('cPower', $option->cPower) }}"
                                       min="0"
                                       required>
                                @error('cPower')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cPowerLimit" class="form-label">Giới hạn sức mạnh</label>
                                <input type="number"
                                       class="form-control @error('cPowerLimit') is-invalid @enderror"
                                       id="cPowerLimit"
                                       name="cPowerLimit"
                                       value="{{ old('cPowerLimit', $option->cPowerLimit) }}"
                                       min="0"
                                       required>
                                @error('cPowerLimit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cTiemNang" class="form-label">Tiềm năng</label>
                                <input type="number"
                                       class="form-control @error('cTiemNang') is-invalid @enderror"
                                       id="cTiemNang"
                                       name="cTiemNang"
                                       value="{{ old('cTiemNang', $option->cTiemNang) }}"
                                       min="0"
                                       required>
                                @error('cTiemNang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-heart me-2"></i>
                        Chỉ số cơ bản
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="cHPGoc" class="form-label">HP gốc</label>
                                <input type="number"
                                       class="form-control @error('cHPGoc') is-invalid @enderror"
                                       id="cHPGoc"
                                       name="cHPGoc"
                                       value="{{ old('cHPGoc', $option->cHPGoc) }}"
                                       min="0"
                                       required>
                                @error('cHPGoc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="cMPGoc" class="form-label">MP gốc</label>
                                <input type="number"
                                       class="form-control @error('cMPGoc') is-invalid @enderror"
                                       id="cMPGoc"
                                       name="cMPGoc"
                                       value="{{ old('cMPGoc', $option->cMPGoc) }}"
                                       min="0"
                                       required>
                                @error('cMPGoc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="cHP" class="form-label">HP hiện tại</label>
                                <input type="number"
                                       class="form-control @error('cHP') is-invalid @enderror"
                                       id="cHP"
                                       name="cHP"
                                       value="{{ old('cHP', $option->cHP) }}"
                                       min="0"
                                       required>
                                @error('cHP')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="cMP" class="form-label">MP hiện tại</label>
                                <input type="number"
                                       class="form-control @error('cMP') is-invalid @enderror"
                                       id="cMP"
                                       name="cMP"
                                       value="{{ old('cMP', $option->cMP) }}"
                                       min="0"
                                       required>
                                @error('cMP')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="cDamGoc" class="form-label">Sức đánh gốc</label>
                                <input type="number"
                                       class="form-control @error('cDamGoc') is-invalid @enderror"
                                       id="cDamGoc"
                                       name="cDamGoc"
                                       value="{{ old('cDamGoc', $option->cDamGoc) }}"
                                       min="0"
                                       required>
                                @error('cDamGoc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="cDefGoc" class="form-label">Giáp gốc</label>
                                <input type="number"
                                       class="form-control @error('cDefGoc') is-invalid @enderror"
                                       id="cDefGoc"
                                       name="cDefGoc"
                                       value="{{ old('cDefGoc', $option->cDefGoc) }}"
                                       min="0"
                                       required>
                                @error('cDefGoc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="cCriticalGoc" class="form-label">Chí mạng gốc</label>
                                <input type="number"
                                       class="form-control @error('cCriticalGoc') is-invalid @enderror"
                                       id="cCriticalGoc"
                                       name="cCriticalGoc"
                                       value="{{ old('cCriticalGoc', $option->cCriticalGoc) }}"
                                       min="0"
                                       required>
                                @error('cCriticalGoc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('options.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>
                    Hủy
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>
                    Cập nhật
                </button>
            </div>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Thông tin tài khoản
                </h5>
            </div>
            <div class="card-body">
                @if($option->user)
                    <div class="mb-3">
                        <strong>Username:</strong> {{ $option->user->username }}
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong> {{ $option->user->email ?: 'Chưa có' }}
                    </div>
                    <div class="mb-3">
                        <strong>Trạng thái:</strong>
                        @if($option->user->isLock)
                            <span class="badge bg-danger">Bị khóa</span>
                        @elseif($option->user->isLoad)
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-secondary">Không hoạt động</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <strong>Admin:</strong>
                        @if($option->user->isAdmin)
                            <span class="badge bg-danger">Admin</span>
                        @else
                            <span class="badge bg-light text-dark">User</span>
                        @endif
                    </div>
                @else
                    <div class="text-muted">Không tìm thấy thông tin tài khoản</div>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-geo-alt me-2"></i>
                    Vị trí hiện tại
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>Map ID:</strong> {{ $option->mapTemplateId }}
                </div>
                <div class="mb-2">
                    <strong>Tọa độ:</strong> ({{ $option->cx }}, {{ $option->cy }})
                </div>
            </div>
        </div>

        <!-- Task Management Card -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-list-task me-2"></i>
                    Quản lý nhiệm vụ
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('options.update-tasks', $option->playerId) }}">
                    @csrf

                    <div class="mb-3">
                        <p class="text-muted small mb-3">
                            <i class="bi bi-info-circle me-1"></i>
                            Chọn các nhiệm vụ đã hoàn thành cho nhân vật này
                        </p>

                        <!-- Search and Controls -->
                        <div class="mb-3">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text"
                                       class="form-control"
                                       id="taskSearch"
                                       placeholder="Tìm kiếm nhiệm vụ...">
                                <button type="button" class="btn btn-outline-secondary" id="selectAllTasks">
                                    <i class="bi bi-check-all"></i>
                                    Chọn tất cả
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="clearAllTasks">
                                    <i class="bi bi-x-square"></i>
                                    Bỏ chọn
                                </button>
                            </div>
                        </div>

                        <div class="task-list" style="max-height: 400px; overflow-y: auto;" id="taskContainer">
                            @forelse($allTasks as $task)
                                <div class="form-check mb-2">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="completed_tasks[]"
                                           value="{{ $task->taskId }}"
                                           id="task_{{ $task->taskId }}"
                                           {{ in_array($task->taskId, $completedTaskIds) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="task_{{ $task->taskId }}">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <strong>{{ $task->display_name }}</strong>
                                                @if($task->hasSubTasks())
                                                    <span class="badge bg-info ms-1">{{ $task->sub_tasks_count }} bước</span>
                                                @endif
                                                <br>
                                                <small class="text-muted">{{ $task->formatted_detail }}</small>
                                                <br>
                                                <span class="badge bg-primary">Nhiệm vụ</span>
                                            </div>
                                            @if($task->taskId == $currentTaskId)
                                                <span class="badge bg-primary">
                                                    <i class="bi bi-play-fill"></i>
                                                    Tiếp theo
                                                </span>
                                            @elseif(in_array($task->taskId, $completedTaskIds))
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-lg"></i>
                                                    Hoàn thành
                                                </span>
                                            @endif
                                        </div>
                                    </label>
                                </div>
                                <hr class="my-2">
                            @empty
                                <div class="text-center text-muted py-3">
                                    <i class="bi bi-inbox display-4"></i>
                                    <p class="mt-2">Không có nhiệm vụ nào trong hệ thống</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            <i class="bi bi-play-fill me-1"></i>
                            Task tiếp theo: <span class="fw-bold" id="nextTaskDisplay">{{ $currentTaskId }}</span>
                            <br>
                            <i class="bi bi-check-square me-1"></i>
                            Đã hoàn thành: <span class="fw-bold" id="completedCount">{{ count($completedTaskIds) }}</span> / {{ $allTasks->count() }}
                        </div>
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="bi bi-check-lg me-1"></i>
                            Cập nhật nhiệm vụ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const taskSearch = document.getElementById('taskSearch');
    const taskContainer = document.getElementById('taskContainer');
    const selectAllBtn = document.getElementById('selectAllTasks');
    const clearAllBtn = document.getElementById('clearAllTasks');

    // Search functionality
    if (taskSearch && taskContainer) {
        taskSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const taskItems = taskContainer.querySelectorAll('.form-check');

            taskItems.forEach(item => {
                const label = item.querySelector('.form-check-label');
                const text = label.textContent.toLowerCase();

                if (text.includes(searchTerm)) {
                    item.style.display = 'block';
                    item.nextElementSibling.style.display = 'block'; // Show hr
                } else {
                    item.style.display = 'none';
                    item.nextElementSibling.style.display = 'none'; // Hide hr
                }
            });
        });
    }

    // Select all functionality
    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function() {
            const visibleCheckboxes = taskContainer.querySelectorAll('.form-check:not([style*="display: none"]) input[type="checkbox"]');
            visibleCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            updateTaskCount();
        });
    }

    // Clear all functionality
    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', function() {
            const visibleCheckboxes = taskContainer.querySelectorAll('.form-check:not([style*="display: none"]) input[type="checkbox"]');
            visibleCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            updateTaskCount();
        });
    }

    // Update task count when checkboxes change
    function updateTaskCount() {
        const totalTasks = taskContainer.querySelectorAll('input[type="checkbox"]').length;
        const completedTasks = taskContainer.querySelectorAll('input[type="checkbox"]:checked').length;

        const countElement = document.querySelector('.fw-bold');
        if (countElement) {
            countElement.textContent = completedTasks;
        }
    }

    // Add event listeners to all checkboxes for auto-check logic
    taskContainer.addEventListener('change', function(e) {
        if (e.target.type === 'checkbox') {
            handleTaskCheck(e.target);
            updateTaskCount();
        }
    });

    // Auto-check logic: when checking a task, auto-check all previous tasks
    function handleTaskCheck(checkbox) {
        const taskId = parseInt(checkbox.value);
        const allCheckboxes = taskContainer.querySelectorAll('input[type="checkbox"]');

        if (checkbox.checked) {
            // When checking a task, check all tasks with lower IDs
            allCheckboxes.forEach(cb => {
                const cbTaskId = parseInt(cb.value);
                if (cbTaskId <= taskId) {
                    cb.checked = true;
                }
            });
        } else {
            // When unchecking a task, uncheck all tasks with higher IDs
            allCheckboxes.forEach(cb => {
                const cbTaskId = parseInt(cb.value);
                if (cbTaskId >= taskId) {
                    cb.checked = false;
                }
            });
        }
    }

    // Update task count and next task display
    function updateTaskCount() {
        const checkedBoxes = taskContainer.querySelectorAll('input[type="checkbox"]:checked');
        const completedCountElement = document.getElementById('completedCount');
        const nextTaskElement = document.getElementById('nextTaskDisplay');

        if (completedCountElement) {
            completedCountElement.textContent = checkedBoxes.length;
        }

        // Find highest checked task ID, next task is highest + 1
        let highestTaskId = -1;
        checkedBoxes.forEach(cb => {
            const taskId = parseInt(cb.value);
            if (taskId > highestTaskId) {
                highestTaskId = taskId;
            }
        });

        const nextTaskId = highestTaskId + 1;
        if (nextTaskElement) {
            nextTaskElement.textContent = nextTaskId;
        }
    }
});
</script>
@endpush
