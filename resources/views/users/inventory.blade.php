@extends('layouts.admin')

@section('title', 'Túi đồ - ' . $user->username)

@section('header')
    <h1 class="h2">
        <i class="bi bi-bag me-2"></i>
        Túi đồ: {{ $user->username }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Quay lại danh sách
            </a>
            <a href="{{ route('users.show', $user) }}" class="btn btn-outline-info">
                <i class="bi bi-eye me-1"></i>
                Xem chi tiết
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- User Info Card -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-1">
                            <i class="bi bi-person-circle me-2"></i>
                            {{ $user->username }}
                            @if($user->isAdmin)
                                <span class="badge bg-danger ms-2">Admin</span>
                            @endif
                        </h5>
                        <p class="text-muted mb-0">
                            Player ID: {{ $user->playerId }} |
                            Level: {{ $user->level }} |
                            Xu: {{ number_format($user->money) }} |
                            Lượng: {{ number_format($user->danap) }}
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <span class="badge bg-{{ $user->isLoad ? 'success' : 'secondary' }} fs-6">
                            {{ $user->statusText }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Tabs -->
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="inventoryTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="bag-tab" data-bs-toggle="tab" data-bs-target="#bag-pane"
                                type="button" role="tab" aria-controls="bag-pane" aria-selected="true">
                            <i class="bi bi-bag me-2"></i>
                            Hành trang
                            @if($inventory && $inventory->parsedItems)
                                <span class="badge bg-primary ms-2">{{ count($inventory->parsedItems) }}</span>
                            @endif
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="box-tab" data-bs-toggle="tab" data-bs-target="#box-pane"
                                type="button" role="tab" aria-controls="box-pane" aria-selected="false">
                            <i class="bi bi-box me-2"></i>
                            Rương đồ
                            @if($box && $box->parsedItems)
                                <span class="badge bg-info ms-2">{{ count($box->parsedItems) }}</span>
                            @endif
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content" id="inventoryTabContent">
                    <!-- Bag Tab -->
                    <div class="tab-pane fade show active" id="bag-pane" role="tabpanel" aria-labelledby="bag-tab">
                        @if($inventory && $inventory->parsedItems && count($inventory->parsedItems) > 0)
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">
                                            <i class="bi bi-bag me-2"></i>
                                            Hành trang ({{ count($inventory->parsedItems) }}/{{ $inventory->maxCount ?? 20 }})
                                        </h6>
                                        <div class="progress" style="width: 200px;">
                                            <div class="progress-bar" role="progressbar"
                                                 style="width: {{ (count($inventory->parsedItems) / ($inventory->maxCount ?? 20)) * 100 }}%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                @foreach($inventory->parsedItems as $item)
                                    @php
                                        $template = $itemTemplates->get($item['id']);
                                    @endphp
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card h-100 border-start border-3 border-{{ $template ? ($template->rarityColor == 'text-warning' ? 'warning' : ($template->rarityColor == 'text-danger' ? 'danger' : ($template->rarityColor == 'text-info' ? 'info' : 'success'))) : 'secondary' }}">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="card-title mb-0 {{ $template ? $template->rarityColor : 'text-muted' }}">
                                                        @if($template)
                                                            {{ $template->name }}
                                                        @else
                                                            Item ID: {{ $item['id'] }}
                                                        @endif
                                                    </h6>
                                                    <div class="text-end">
                                                        <small class="text-muted">Slot {{ $item['slot'] }}</small>
                                                        @if($item['isLock'])
                                                            <br><i class="bi bi-lock-fill text-warning" title="Đã khóa"></i>
                                                        @endif
                                                    </div>
                                                </div>

                                                @if($template)
                                                    <p class="card-text small text-muted mb-2">{{ $template->description }}</p>
                                                    <div class="row text-center mb-2">
                                                        <div class="col-4">
                                                            <small class="text-muted">Type</small><br>
                                                            <span class="badge bg-secondary">{{ $template->typeText }}</span>
                                                        </div>
                                                        <div class="col-4">
                                                            <small class="text-muted">Level</small><br>
                                                            <span class="fw-bold">{{ $template->level }}</span>
                                                        </div>
                                                        <div class="col-4">
                                                            <small class="text-muted">Số lượng</small><br>
                                                            <span class="fw-bold text-primary">{{ $item['quantity'] }}</span>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if(!empty($item['options']))
                                                    @php
                                                        $setInfo = \App\Helpers\ItemOptionHelper::getSetInfo($item['options'], $item['id'], $template);
                                                        $requirements = \App\Helpers\ItemOptionHelper::getRequirementInfo($item['options'], $template);
                                                        $totalPower = \App\Helpers\ItemOptionHelper::calculateTotalPower($item['options'], $item);
                                                    @endphp

                                                    @if(!empty($setInfo))
                                                        <div class="mt-2">
                                                            <small class="text-muted">Set Items:</small>
                                                            <div class="mt-1">
                                                                @foreach($setInfo as $set)
                                                                    <div class="badge bg-{{ $set['color'] }} me-1 mb-1">
                                                                        {{ $set['name'] }}
                                                                    </div>
                                                                    <small class="text-muted d-block">{{ $set['description'] }}</small>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if(!empty($requirements))
                                                        <div class="mt-2">
                                                            <small class="text-muted">Yêu cầu:</small>
                                                            <div class="mt-1">
                                                                @foreach($requirements as $req)
                                                                    <div class="badge bg-{{ $req['color'] }} me-1 mb-1">
                                                                        {{ $req['text'] }}
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <div class="mt-2">
                                                        <small class="text-muted">Thuộc tính:</small>
                                                        <div class="mt-1">
                                                            @foreach($item['options'] as $option)
                                                                @php
                                                                    $optionInfo = \App\Helpers\ItemOptionHelper::getOptionInfo($option[0], $option[1]);
                                                                @endphp
                                                                <span class="badge bg-{{ $optionInfo['color'] }} me-1 mb-1" title="Option ID: {{ $option[0] }}">
                                                                    {{ $optionInfo['text'] }}: {{ $optionInfo['formatted_value'] }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    @if($totalPower > 0)
                                                        <div class="mt-2">
                                                            <small class="text-muted">Sức mạnh ước tính:</small>
                                                            <span class="badge bg-primary ms-1">{{ number_format($totalPower) }}</span>
                                                        </div>
                                                    @endif
                                                @endif

                                                @php
                                                    $hasEnchants = collect($item['enchants'])->filter(function($e) { return $e != -1; })->count() > 0;
                                                @endphp
                                                @if($hasEnchants)
                                                    <div class="mt-2">
                                                        <small class="text-muted">Cường hóa:</small>
                                                        <div class="mt-1">
                                                            @foreach($item['enchants'] as $index => $enchant)
                                                                @if($enchant != -1)
                                                                    <span class="badge bg-warning me-1">{{ $enchant }}</span>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-bag display-1 text-muted"></i>
                                <h5 class="mt-3 text-muted">Hành trang trống</h5>
                                <p class="text-muted">Người chơi chưa có item nào trong hành trang.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Box Tab -->
                    <div class="tab-pane fade" id="box-pane" role="tabpanel" aria-labelledby="box-tab">
                        @if($box && $box->parsedItems && count($box->parsedItems) > 0)
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">
                                            <i class="bi bi-box me-2"></i>
                                            Rương đồ ({{ count($box->parsedItems) }}/{{ $box->maxCount ?? 20 }})
                                        </h6>
                                        <div class="progress" style="width: 200px;">
                                            <div class="progress-bar bg-info" role="progressbar"
                                                 style="width: {{ (count($box->parsedItems) / ($box->maxCount ?? 20)) * 100 }}%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                @foreach($box->parsedItems as $item)
                                    @php
                                        $template = $itemTemplates->get($item['id']);
                                    @endphp
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card h-100 border-start border-3 border-{{ $template ? ($template->rarityColor == 'text-warning' ? 'warning' : ($template->rarityColor == 'text-danger' ? 'danger' : ($template->rarityColor == 'text-info' ? 'info' : 'success'))) : 'secondary' }}">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="card-title mb-0 {{ $template ? $template->rarityColor : 'text-muted' }}">
                                                        @if($template)
                                                            {{ $template->name }}
                                                        @else
                                                            Item ID: {{ $item['id'] }}
                                                        @endif
                                                    </h6>
                                                    <div class="text-end">
                                                        <small class="text-muted">Slot {{ $item['slot'] }}</small>
                                                        @if($item['isLock'])
                                                            <br><i class="bi bi-lock-fill text-warning" title="Đã khóa"></i>
                                                        @endif
                                                    </div>
                                                </div>

                                                @if($template)
                                                    <p class="card-text small text-muted mb-2">{{ $template->description }}</p>
                                                    <div class="row text-center mb-2">
                                                        <div class="col-4">
                                                            <small class="text-muted">Type</small><br>
                                                            <span class="badge bg-secondary">{{ $template->typeText }}</span>
                                                        </div>
                                                        <div class="col-4">
                                                            <small class="text-muted">Level</small><br>
                                                            <span class="fw-bold">{{ $template->level }}</span>
                                                        </div>
                                                        <div class="col-4">
                                                            <small class="text-muted">Số lượng</small><br>
                                                            <span class="fw-bold text-primary">{{ $item['quantity'] }}</span>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if(!empty($item['options']))
                                                    @php
                                                        $setInfo = \App\Helpers\ItemOptionHelper::getSetInfo($item['options'], $item['id'], $template);
                                                        $requirements = \App\Helpers\ItemOptionHelper::getRequirementInfo($item['options'], $template);
                                                        $totalPower = \App\Helpers\ItemOptionHelper::calculateTotalPower($item['options'], $item);
                                                    @endphp

                                                    @if(!empty($setInfo))
                                                        <div class="mt-2">
                                                            <small class="text-muted">Set Items:</small>
                                                            <div class="mt-1">
                                                                @foreach($setInfo as $set)
                                                                    <div class="badge bg-{{ $set['color'] }} me-1 mb-1">
                                                                        {{ $set['name'] }}
                                                                    </div>
                                                                    <small class="text-muted d-block">{{ $set['description'] }}</small>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if(!empty($requirements))
                                                        <div class="mt-2">
                                                            <small class="text-muted">Yêu cầu:</small>
                                                            <div class="mt-1">
                                                                @foreach($requirements as $req)
                                                                    <div class="badge bg-{{ $req['color'] }} me-1 mb-1">
                                                                        {{ $req['text'] }}
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <div class="mt-2">
                                                        <small class="text-muted">Thuộc tính:</small>
                                                        <div class="mt-1">
                                                            @foreach($item['options'] as $option)
                                                                @php
                                                                    $optionInfo = \App\Helpers\ItemOptionHelper::getOptionInfo($option[0], $option[1]);
                                                                @endphp
                                                                <span class="badge bg-{{ $optionInfo['color'] }} me-1 mb-1" title="Option ID: {{ $option[0] }}">
                                                                    {{ $optionInfo['text'] }}: {{ $optionInfo['formatted_value'] }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    @if($totalPower > 0)
                                                        <div class="mt-2">
                                                            <small class="text-muted">Sức mạnh ước tính:</small>
                                                            <span class="badge bg-primary ms-1">{{ number_format($totalPower) }}</span>
                                                        </div>
                                                    @endif
                                                @endif

                                                @php
                                                    $hasEnchants = collect($item['enchants'])->filter(function($e) { return $e != -1; })->count() > 0;
                                                @endphp
                                                @if($hasEnchants)
                                                    <div class="mt-2">
                                                        <small class="text-muted">Cường hóa:</small>
                                                        <div class="mt-1">
                                                            @foreach($item['enchants'] as $index => $enchant)
                                                                @if($enchant != -1)
                                                                    <span class="badge bg-warning me-1">{{ $enchant }}</span>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-box display-1 text-muted"></i>
                                <h5 class="mt-3 text-muted">Rương đồ trống</h5>
                                <p class="text-muted">Người chơi chưa có item nào trong rương đồ.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
