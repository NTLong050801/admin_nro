<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\GameUser;
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

        return view('options.edit', compact('option'));
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
            'xu' => ['required', 'integer', 'min:0'],
            'luong' => ['required', 'integer', 'min:0'],
            'luongKhoa' => ['required', 'integer', 'min:0'],
            'cPower' => ['required', 'integer', 'min:0'],
            'cPowerLimit' => ['required', 'integer', 'min:0'],
            'cHPGoc' => ['required', 'integer', 'min:0'],
            'cMPGoc' => ['required', 'integer', 'min:0'],
            'cHP' => ['required', 'integer', 'min:0'],
            'cMP' => ['required', 'integer', 'min:0'],
            'cDamGoc' => ['required', 'integer', 'min:0'],
            'cDefGoc' => ['required', 'integer', 'min:0'],
            'cCriticalGoc' => ['required', 'integer', 'min:0'],
            'cTiemNang' => ['required', 'integer', 'min:0'],
            'mapTemplateId' => ['required', 'integer', 'min:0'],
            'cx' => ['required', 'integer'],
            'cy' => ['required', 'integer'],
            'cStamina' => ['required', 'integer', 'min:0'],
            'cMaxStamina' => ['required', 'integer', 'min:0'],
            'pointEvent' => ['required', 'integer', 'min:0'],
            'totalGold' => ['required', 'integer', 'min:0'],
            'pointVip' => ['required', 'integer', 'min:0'],
            'pointEventVIP' => ['required', 'integer', 'min:0'],
            'clanPoint' => ['required', 'integer', 'min:0'],
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
}
