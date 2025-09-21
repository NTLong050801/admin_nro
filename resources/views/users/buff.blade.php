@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">🎁 Thêm Item: {{ $user->username }}</h1>
                    <p class="text-muted mb-0">
                        <i class="bi bi-person-badge me-1"></i>
                        Player ID: {{ $user->playerId }} |
                        <i class="bi bi-gem me-1"></i>
                        Level: {{ $user->level }} |
                        <i class="bi bi-coin me-1"></i>
                        Vàng: {{ number_format($user->money) }}
                    </p>
                </div>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>
                    Quay lại
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Có lỗi xảy ra:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Add Item Form -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-plus-circle me-2"></i>
                        Thêm Item Mới
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('users.buff.store', $user) }}">
                        @csrf
                        <input type="hidden" name="action" value="add_item">

                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label">Vị trí:</label>
                                <select name="location" class="form-select select2" required>
                                    <option value="">Chọn vị trí</option>
                                    <option value="inventory">Hành trang</option>
                                    <option value="box">Rương đồ</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Hành tinh:</label>
                                <select id="planet-select" class="form-select select2" required>
                                    <option value="">Chọn hành tinh</option>
                                    <option value="0">Trái Đất</option>
                                    <option value="1">Namek</option>
                                    <option value="2">Xayda</option>
                                    <option value="3">Items khác</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Item:</label>
                                <select id="item-select" name="item_id" class="form-select select2" required disabled>
                                    <option value="">Chọn hành tinh trước</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Số lượng:</label>
                                <input type="number" name="quantity" class="form-control" min="1" value="1" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Cường hóa:</label>
                                <input type="number" name="upgrade_level" class="form-control" min="0" value="0">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-plus-lg me-1"></i>
                                    Thêm
                                </button>
                            </div>
                        </div>

                        <!-- Dynamic Options Section - Initially Hidden -->
                        <div id="options-section" class="mt-3" style="display: none;">
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3">
                                        <i class="bi bi-gear me-1"></i>
                                        Thuộc tính Item (Tùy chọn)
                                    </h6>
                                </div>
                            </div>

                            <div id="options-container" class="row">
                                <!-- Options will be loaded here dynamically -->
                            </div>

                            <div class="row mt-2">
                                <div class="col-12">
                                    <button type="button" id="add-option-btn" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-plus-lg me-1"></i>
                                        Thêm thuộc tính
                                    </button>
                                    <button type="button" id="hide-options-btn" class="btn btn-outline-secondary btn-sm ms-2">
                                        <i class="bi bi-eye-slash me-1"></i>
                                        Ẩn thuộc tính
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Show Options Button -->
                        <div class="row mt-2">
                            <div class="col-12">
                                <button type="button" id="show-options-btn" class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i>
                                    Thêm thuộc tính cho Item
                                </button>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-12">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <strong>Hướng dẫn:</strong> Nhập Item ID từ itemtemplate, chọn vị trí (hành trang/rương),
                                    thiết lập cường hóa và thuộc tính. Item sẽ được thêm vào slot trống đầu tiên.
                                </small>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs mb-4" id="buffTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory" type="button" role="tab">
                        <i class="bi bi-bag me-1"></i>
                        Hành trang ({{ count($inventoryItems) }}/50)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="box-tab" data-bs-toggle="tab" data-bs-target="#box" type="button" role="tab">
                        <i class="bi bi-box me-1"></i>
                        Rương đồ ({{ count($boxItems) }}/100)
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="buffTabsContent">
                <!-- Inventory Tab -->
                <div class="tab-pane fade show active" id="inventory" role="tabpanel">
                    <div class="row">
                        @forelse($inventoryItems as $slot => $item)
                            @if($item['id'] > 0)
                                @php
                                    $template = $itemTemplates[$item['id']] ?? null;
                                @endphp
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 border-primary">
                                        <div class="card-header bg-primary text-white">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">
                                                    <i class="bi bi-gem me-1"></i>
                                                    Slot {{ $slot }}
                                                </h6>
                                                <span class="badge bg-light text-dark">
                                                    ID: {{ $item['id'] }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h6 class="card-title text-primary">
                                                {{ $template->name ?? 'Unknown Item' }}
                                            </h6>
                                            <div class="mb-3">
                                                <small class="text-muted">
                                                    <i class="bi bi-arrow-up-circle me-1"></i>
                                                    Cường hóa: +{{ $item['upgrade_level'] >= 0 ? $item['upgrade_level'] : 0 }}
                                                </small>
                                                @if(!empty($item['options']))
                                                    <div class="mt-1">
                                                        <small class="text-info">
                                                            <i class="bi bi-star me-1"></i>
                                                            {{ count($item['options']) }} thuộc tính
                                                        </small>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Edit Button -->
                                            <div class="mt-3">
                                                <a href="{{ route('users.buff.item', [$user, 'inventory', $slot]) }}"
                                                   class="btn btn-primary btn-sm w-100">
                                                    <i class="bi bi-pencil me-1"></i>
                                                    Edit Item
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info text-center">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Hành trang trống
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Box Tab -->
                <div class="tab-pane fade" id="box" role="tabpanel">
                    <div class="row">
                        @forelse($boxItems as $slot => $item)
                            @if($item['id'] > 0)
                                @php
                                    $template = $itemTemplates[$item['id']] ?? null;
                                @endphp
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 border-success">
                                        <div class="card-header bg-success text-white">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">
                                                    <i class="bi bi-box me-1"></i>
                                                    Slot {{ $slot }}
                                                </h6>
                                                <span class="badge bg-light text-dark">
                                                    ID: {{ $item['id'] }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h6 class="card-title text-success">
                                                {{ $template->name ?? 'Unknown Item' }}
                                            </h6>
                                            <div class="mb-3">
                                                <small class="text-muted">
                                                    <i class="bi bi-arrow-up-circle me-1"></i>
                                                    Cường hóa: +{{ $item['upgrade_level'] >= 0 ? $item['upgrade_level'] : 0 }}
                                                </small>
                                                @if(!empty($item['options']))
                                                    <div class="mt-1">
                                                        <small class="text-info">
                                                            <i class="bi bi-star me-1"></i>
                                                            {{ count($item['options']) }} thuộc tính
                                                        </small>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Edit Button -->
                                            <div class="mt-3">
                                                <a href="{{ route('users.buff.item', [$user, 'box', $slot]) }}"
                                                   class="btn btn-primary btn-sm w-100">
                                                    <i class="bi bi-pencil me-1"></i>
                                                    Edit Item
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info text-center">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Rương đồ trống
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
    const planetSelect = document.getElementById('planet-select');
    const itemSelect = document.getElementById('item-select');

    // Use Select2 event instead of DOM event
    $('#planet-select').on('select2:select', function(e) {
        const planet = e.params.data.id;

        // Reset item select
        $('#item-select').html('<option value="">Đang tải...</option>').prop('disabled', true);
        $('#item-select').select2('destroy').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Đang tải...',
            allowClear: false
        });

        if (!planet) {
            $('#item-select').html('<option value="">Chọn hành tinh trước</option>').prop('disabled', true);
            $('#item-select').select2('destroy').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Chọn hành tinh trước',
                allowClear: false
            });
            return;
        }

        // Fetch items by planet
        fetch(`/api/items-by-planet/${planet}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(items => {
                // Build options HTML
                let optionsHtml = '<option value="">Chọn item</option>';
                items.forEach(item => {
                    optionsHtml += `<option value="${item.id}">${item.display_text}</option>`;
                });

                // Update select and reinitialize Select2
                $('#item-select').html(optionsHtml).prop('disabled', false);
                $('#item-select').select2('destroy').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'Chọn item',
                    allowClear: false
                });
            })
            .catch(error => {
                console.error('Error fetching items:', error);
                $('#item-select').html('<option value="">Lỗi tải dữ liệu</option>').prop('disabled', true);
                $('#item-select').select2('destroy').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'Lỗi tải dữ liệu',
                    allowClear: false
                });

                // Show error message
                const alert = document.createElement('div');
                alert.className = 'alert alert-danger alert-dismissible fade show mt-2';
                alert.innerHTML = `
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Không thể tải danh sách items. Vui lòng thử lại.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                $('#planet-select').parent().append(alert);

                // Auto dismiss after 5 seconds
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 5000);
            });
    });

    // Dynamic Options Management
    let availableOptions = [];
    let optionCounter = 0;

    // Load available options from API
    fetch('/api/item-options')
        .then(response => response.json())
        .then(data => {
            availableOptions = data;
        })
        .catch(error => {
            console.error('Error loading options:', error);
        });

    // Show/Hide options section
    document.getElementById('show-options-btn').addEventListener('click', function() {
        document.getElementById('options-section').style.display = 'block';
        document.getElementById('show-options-btn').style.display = 'none';
    });

    document.getElementById('hide-options-btn').addEventListener('click', function() {
        document.getElementById('options-section').style.display = 'none';
        document.getElementById('show-options-btn').style.display = 'block';
        // Clear all options when hiding
        document.getElementById('options-container').innerHTML = '';
    });

    // Add option functionality for main form
    document.getElementById('add-option-btn').addEventListener('click', function() {
        addOptionRow('options-container', 'option');
    });

    // No longer needed - using dedicated buff page instead

    // Function to add option row
    function addOptionRow(container, namePrefix) {
        if (typeof container === 'string') {
            container = document.getElementById(container);
        }

        const optionId = `${namePrefix}_${optionCounter++}`;

        const optionRow = document.createElement('div');
        optionRow.className = 'col-md-6 mb-2 option-row';
        optionRow.innerHTML = `
            <div class="input-group input-group-sm">
                <select name="${namePrefix}_id[]" class="form-select form-select-sm option-select select2-dynamic" required>
                    <option value="">Chọn thuộc tính</option>
                    ${availableOptions.map(opt =>
                        `<option value="${opt.id}">${opt.display_name}</option>`
                    ).join('')}
                </select>
                <input type="number" name="${namePrefix}_value[]" class="form-control form-control-sm"
                       placeholder="Giá trị" min="0" required>
                <button type="button" class="btn btn-outline-danger btn-sm remove-option-btn">
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
    }

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
});
</script>
@endpush

@endsection
