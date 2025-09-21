@extends('layouts.admin')

@section('title', 'Buff Item - ' . ($itemTemplate->name ?? 'Unknown Item'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">
                        <i class="bi bi-gem me-2"></i>
                        Buff Item: {{ $itemTemplate->name ?? 'Unknown Item' }}
                    </h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('users.show', $user) }}">{{ $user->username }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('users.buff', $user) }}">Buff Items</a></li>
                            <li class="breadcrumb-item active">{{ $itemTemplate->name ?? 'Item' }}</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('users.buff', $user) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Quay lại
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Item Information Card -->
                <div class="col-md-4">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-info-circle me-1"></i>
                                Thông tin Item
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>ID:</strong>
                                <span class="badge bg-secondary">{{ $item['id'] }}</span>
                            </div>

                            <div class="mb-3">
                                <strong>Tên:</strong>
                                <span class="text-primary fw-bold">{{ $itemTemplate->name ?? 'Unknown Item' }}</span>
                            </div>

                            <div class="mb-3">
                                <strong>Vị trí:</strong>
                                <span class="badge bg-{{ $location === 'inventory' ? 'info' : 'success' }}">
                                    {{ $location === 'inventory' ? 'Hành trang' : 'Rương đồ' }} - Slot {{ $slot }}
                                </span>
                            </div>

                            <div class="mb-3">
                                <strong>Số lượng:</strong>
                                <span class="badge bg-warning">{{ $item['quantity'] }}</span>
                            </div>

                            <div class="mb-3">
                                <strong>Cường hóa hiện tại:</strong>
                                <span class="badge bg-{{ $item['upgrade_level'] > 0 ? 'success' : 'secondary' }}">
                                    +{{ $item['upgrade_level'] >= 0 ? $item['upgrade_level'] : 0 }}
                                </span>
                            </div>

                            @if(!empty($item['options']))
                            <div class="mb-3">
                                <strong>Thuộc tính hiện tại:</strong>
                                <div class="mt-2">
                                    @foreach($item['options'] as $option)
                                        @php
                                            $optionTemplate = $itemOptions[$option[0]] ?? null;
                                            $optionName = $optionTemplate ? str_replace('#', $option[1], $optionTemplate->name) : "Option {$option[0]}: {$option[1]}";
                                        @endphp
                                        <span class="badge bg-info me-1 mb-1">{{ $optionName }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Buff Form Card -->
                <div class="col-md-8">
                    <div class="card border-warning">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="bi bi-lightning me-1"></i>
                                Chỉnh sửa Item
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('users.buff.item.update', [$user, $location, $slot]) }}">
                                @csrf

                                <!-- Upgrade Level -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="bi bi-arrow-up-circle me-1"></i>
                                            Cường hóa (0-16):
                                        </label>
                                        <input type="number" name="upgrade_level" class="form-control"
                                               min="0"
                                               value="{{ $item['upgrade_level'] >= 0 ? $item['upgrade_level'] : 0 }}"
                                               placeholder="Nhập level cường hóa">
                                        <small class="text-muted">Hiện tại: +{{ $item['upgrade_level'] >= 0 ? $item['upgrade_level'] : 0 }}</small>
                                    </div>
                                </div>

                                <!-- Options Section -->
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <label class="form-label fw-bold mb-0">
                                            <i class="bi bi-gear me-1"></i>
                                            Thuộc tính Item:
                                        </label>
                                        <button type="button" id="add-option-btn" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-plus-lg me-1"></i>
                                            Thêm thuộc tính
                                        </button>
                                    </div>

                                    <div id="options-container">
                                        <!-- Existing options will be loaded here -->
                                        @if(!empty($item['options']))
                                            @foreach($item['options'] as $index => $option)
                                                <div class="row mb-2 option-row">
                                                    <div class="col-md-6">
                                                        <select name="option_id[]" class="form-select option-select select2" required>
                                                            <option value="">Chọn thuộc tính</option>
                                                            @foreach($itemOptions as $optionTemplate)
                                                                <option value="{{ $optionTemplate->id }}"
                                                                        {{ $optionTemplate->id == $option[0] ? 'selected' : '' }}>
                                                                    {{ str_replace('#', '', $optionTemplate->name) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="number" name="option_value[]" class="form-control"
                                                               placeholder="Giá trị" min="0" value="{{ $option[1] }}" required>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-outline-danger remove-option-btn">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="bi bi-lightning me-1"></i>
                                            Cập nhật Item
                                        </button>
                                        <a href="{{ route('users.buff', $user) }}" class="btn btn-outline-secondary btn-lg ms-2">
                                            <i class="bi bi-x-lg me-1"></i>
                                            Hủy
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: function() {
            return $(this).find('option:first').text();
        },
        allowClear: false
    });
    let optionCounter = {{ count($item['options'] ?? []) }};

    // Add option functionality
    document.getElementById('add-option-btn').addEventListener('click', function() {
        addOptionRow();
    });

    // Remove option functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-option-btn') || e.target.parentElement.classList.contains('remove-option-btn')) {
            const optionRow = e.target.closest('.option-row');
            if (optionRow) {
                // Destroy Select2 before removing
                $(optionRow).find('.select2-dynamic').select2('destroy');
                optionRow.remove();
            }
        }
    });

    function addOptionRow() {
        const container = document.getElementById('options-container');
        const optionRow = document.createElement('div');
        optionRow.className = 'row mb-2 option-row';

        // Create select options HTML
        let optionsHtml = '<option value="">Chọn thuộc tính</option>';
        @foreach($itemOptions as $optionTemplate)
            optionsHtml += '<option value="{{ $optionTemplate->id }}">{{ str_replace('#', '', $optionTemplate->name) }}</option>';
        @endforeach

        optionRow.innerHTML = `
            <div class="col-md-6">
                <select name="option_id[]" class="form-select option-select select2-dynamic" required>
                    ${optionsHtml}
                </select>
            </div>
            <div class="col-md-4">
                <input type="number" name="option_value[]" class="form-control"
                       placeholder="Giá trị" min="0" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-outline-danger remove-option-btn">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;

        container.appendChild(optionRow);

        // Initialize Select2 for the new option select
        $(optionRow).find('.select2-dynamic').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Chọn thuộc tính',
            allowClear: false
        });

        optionCounter++;
    }
});
</script>
@endpush
