<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\GameUser;
use App\Models\Task;
use App\Models\UserTask;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Option::query();

        // Search by character name or playerId
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('cName', 'like', '%' . $search . '%')
                  ->orWhere('playerId', 'like', '%' . $search . '%');
            });
        }

        // Filter by class
        if ($request->filled('class')) {
            $query->where('nClassId', $request->get('class'));
        }

        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('cgender', $request->get('gender'));
        }

        // Filter by power range
        if ($request->filled('power_min')) {
            $query->where('cPower', '>=', $request->get('power_min'));
        }
        if ($request->filled('power_max')) {
            $query->where('cPower', '<=', $request->get('power_max'));
        }

        // Order by power descending by default
        $sortBy = $request->get('sort', 'cPower');
        $sortDirection = $request->get('direction', 'desc');

        $validSorts = ['cPower', 'xu', 'luong', 'cName', 'playerId', 'lastTime'];
        if (in_array($sortBy, $validSorts)) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $options = $query->paginate(15)->withQueryString();

        // Load users manually for each option
        $playerIds = $options->pluck('playerId')->toArray();
        $users = GameUser::whereIn('playerId', $playerIds)->get()->keyBy('playerId');

        // Attach users to options
        foreach ($options as $option) {
            $option->user = $users->get($option->playerId);
        }

        return view('options.index', compact('options'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $option = Option::findOrFail($id);
        $option->user = GameUser::where('playerId', $option->playerId)->first();

        // Load all available tasks
        $allTasks = Task::orderBy('taskId')->get();

        // Get current task ID from cTaskId column (this is the NEXT task to do)
        $currentTaskId = $option->cTaskId ?? 0;

        // Load user's completed tasks from arrtasks
        $userTask = UserTask::where('playerId', $option->playerId)->first();
        $completedTaskIds = $userTask ? $userTask->completed_task_ids : [];

        // If no arrtasks record but cTaskId > 0, then tasks 0 to (cTaskId-1) are completed
        if (!$userTask && $currentTaskId > 0) {
            $completedTaskIds = range(0, $currentTaskId - 1);
        }

        return view('options.edit', compact('option', 'allTasks', 'completedTaskIds', 'currentTaskId', 'userTask'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $option = Option::findOrFail($id);

        $validated = $request->validate([
            'cName' => ['required', 'string', 'max:30'],
            'cgender' => ['required', 'integer', 'in:0,1'],
            'head' => ['required', 'integer', 'min:0'],
            'nClassId' => ['required', 'integer', 'in:0,1,2'],
            'xu' => ['nullable', 'integer', 'min:0'],
            'luong' => ['nullable', 'integer', 'min:0'],
            'luongKhoa' => ['nullable', 'integer', 'min:0'],
            'cPower' => ['nullable', 'integer', 'min:0'],
            'cPowerLimit' => ['nullable', 'integer', 'min:0'],
            'cHPGoc' => ['nullable', 'integer', 'min:0'],
            'cMPGoc' => ['nullable', 'integer', 'min:0'],
            'cHP' => ['nullable', 'integer', 'min:0'],
            'cMP' => ['nullable', 'integer', 'min:0'],
            'cDamGoc' => ['nullable', 'integer', 'min:0'],
            'cDefGoc' => ['nullable', 'integer', 'min:0'],
            'cCriticalGoc' => ['nullable', 'integer', 'min:0'],
            'cTiemNang' => ['nullable', 'integer', 'min:0'],
            'mapTemplateId' => ['nullable', 'integer', 'min:0'],
            'cx' => ['nullable', 'integer'],
            'cy' => ['nullable', 'integer'],
            'cStamina' => ['nullable', 'integer', 'min:0'],
            'cMaxStamina' => ['nullable', 'integer', 'min:0'],
            'pointEvent' => ['nullable', 'integer', 'min:0'],
            'totalGold' => ['nullable', 'integer', 'min:0'],
            'pointVip' => ['nullable', 'integer', 'min:0'],
            'pointEventVIP' => ['nullable', 'integer', 'min:0'],
            'clanPoint' => ['nullable', 'integer', 'min:0'],
            'isCan' => ['boolean'],
        ]);

        // Handle boolean conversion
        $validated['isCan'] = $request->has('isCan');

        $option->update($validated);

        return redirect()->route('options.index')
            ->with('success', 'Thông tin nhân vật đã được cập nhật thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $option = Option::findOrFail($id);
        $option->user = GameUser::where('playerId', $option->playerId)->first();

        return view('options.show', compact('option'));
    }

    /**
     * Reset character stats to default values.
     */
    public function resetStats(Request $request, $id)
    {
        $option = Option::findOrFail($id);

        // Reset stats based on class
        $defaultStats = $this->getDefaultStatsByClass($option->nClassId);

        $option->update($defaultStats);

        return redirect()->back()
            ->with('success', 'Đã reset chỉ số nhân vật về mặc định!');
    }

    /**
     * Get default stats by class.
     */
    private function getDefaultStatsByClass($classId)
    {
        $defaults = [
            0 => [ // Trái Đất
                'cHPGoc' => 200,
                'cMPGoc' => 200,
                'cHP' => 200,
                'cMP' => 200,
                'cDamGoc' => 100,
                'cDefGoc' => 50,
                'cCriticalGoc' => 5,
                'cPower' => 1500,
                'cPowerLimit' => 1500,
            ],
            1 => [ // Namek
                'cHPGoc' => 220,
                'cMPGoc' => 180,
                'cHP' => 220,
                'cMP' => 180,
                'cDamGoc' => 90,
                'cDefGoc' => 60,
                'cCriticalGoc' => 5,
                'cPower' => 1500,
                'cPowerLimit' => 1500,
            ],
            2 => [ // Xayda
                'cHPGoc' => 180,
                'cMPGoc' => 220,
                'cHP' => 180,
                'cMP' => 220,
                'cDamGoc' => 110,
                'cDefGoc' => 40,
                'cCriticalGoc' => 5,
                'cPower' => 1500,
                'cPowerLimit' => 1500,
            ]
        ];

        return $defaults[$classId] ?? $defaults[0];
    }

    /**
     * Teleport character to a specific location.
     */
    public function teleport(Request $request, $id)
    {
        $option = Option::findOrFail($id);

        $validated = $request->validate([
            'mapTemplateId' => ['required', 'integer', 'min:0'],
            'cx' => ['required', 'integer'],
            'cy' => ['required', 'integer'],
        ]);

        $option->update($validated);

        return redirect()->back()
            ->with('success', 'Đã dịch chuyển nhân vật đến vị trí mới!');
    }

    /**
     * Update user tasks (completed tasks).
     */
    public function updateTasks(Request $request, $id)
    {
        $option = Option::findOrFail($id);

        $validated = $request->validate([
            'completed_tasks' => ['nullable', 'array'],
            'completed_tasks.*' => ['integer', 'exists:ngocrong_data.task,taskId']
        ]);

        $completedTaskIds = $validated['completed_tasks'] ?? [];

        // Find the highest completed task ID
        $highestCompletedTaskId = empty($completedTaskIds) ? -1 : max($completedTaskIds);

        // Next task ID is highest completed + 1
        $nextTaskId = $highestCompletedTaskId + 1;

        // Update arrtasks with completed tasks
        $userTask = UserTask::where('playerId', $option->playerId)->first();

        if (!$userTask) {
            $userTask = UserTask::create([
                'playerId' => $option->playerId,
                'arrTask' => []
            ]);
        }

        // Update the completed tasks in arrtasks
        $userTask->updateTasks($completedTaskIds);

        // Update cTaskId in options table (next task to do)

        $option->update([
            'ctaskId' => $nextTaskId
        ]);

        return redirect()->back()
            ->with('success', 'Đã cập nhật nhiệm vụ thành công!');
    }
}
