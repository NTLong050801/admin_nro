<?php

namespace App\Http\Controllers;

use App\Models\GameUser;
use App\Models\PlayerInventory;
use App\Models\PlayerBox;
use App\Models\ItemTemplate;
use App\Models\ItemOptionTemplate;
use App\Helpers\ItemOptionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = GameUser::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $status = $request->get('status');
            switch ($status) {
                case 'active':
                    $query->where('isLoad', true)->where('isLock', false);
                    break;
                case 'locked':
                    $query->where('isLock', true);
                    break;
                case 'inactive':
                    $query->where('isLoad', false);
                    break;
                case 'verified':
                    $query->where('verified', true);
                    break;
                case 'unverified':
                    $query->where('verified', false);
                    break;
                case 'admin':
                    $query->where('isAdmin', true);
                    break;
            }
        }

        // Order by latest
        $query->orderBy('id', 'desc');

        $users = $query->paginate(15)->withQueryString();

        return view('users.index', compact('users'));
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = GameUser::findOrFail($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = GameUser::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = GameUser::findOrFail($id);
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'active' => ['boolean'],
            'admin' => ['boolean'],
            'ban' => ['boolean'],
            'vip' => ['integer', 'min:0', 'max:10'],
            'goldbar' => ['integer', 'min:0'],
            'tongnap' => ['integer', 'min:0'],
            'tichdiem' => ['integer', 'min:0'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        // Handle checkboxes
        $validated['active'] = $request->has('active');
        $validated['admin'] = $request->has('admin');
        $validated['ban'] = $request->has('ban');
        $validated['vip'] = $validated['vip'] ?? 0;

        // Only update password if provided
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        // Set ban_until if user is banned
        if ($validated['ban'] && !$user->ban) {
            $validated['ban_until'] = now()->addDays(30); // Default 30 days ban
        } elseif (!$validated['ban']) {
            $validated['ban_until'] = null;
            $validated['reason'] = null;
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'Tài khoản đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = GameUser::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Tài khoản đã được xóa thành công!');
    }

    /**
     * Toggle user ban status.
     */
    public function toggleBan(Request $request, $id)
    {
        $user = GameUser::findOrFail($id);
        $user->update([
            'ban' => !$user->ban,
            'ban_until' => !$user->ban ? now()->addDays(30) : null,
            'reason' => !$user->ban ? ($request->get('reason') ?? 'Vi phạm quy định') : null,
        ]);

        $status = $user->ban ? 'cấm' : 'bỏ cấm';
        return redirect()->back()
            ->with('success', "Đã {$status} tài khoản thành công!");
    }

    /**
     * Toggle user active status.
     */
    public function toggleActive($id)
    {
        $user = GameUser::findOrFail($id);
        $user->update(['active' => !$user->active]);

        $status = $user->active ? 'kích hoạt' : 'vô hiệu hóa';
        return redirect()->back()
            ->with('success', "Đã {$status} tài khoản thành công!");
    }

    /**
     * Show user inventory (bags and boxes).
     */
    public function inventory($id)
    {
        $user = GameUser::findOrFail($id);

        // Get inventory data
        $inventory = PlayerInventory::where('playerId', $user->playerId)->first();
        $box = PlayerBox::where('playerId', $user->playerId)->first();

        // Get all item IDs to fetch templates
        $itemIds = collect();

        if ($inventory && $inventory->parsedItems) {
            // Convert array to collection and pluck IDs
            $inventoryIds = collect($inventory->parsedItems)->pluck('id');
            $itemIds = $itemIds->merge($inventoryIds);
        }

        if ($box && $box->parsedItems) {
            // Convert array to collection and pluck IDs
            $boxIds = collect($box->parsedItems)->pluck('id');
            $itemIds = $itemIds->merge($boxIds);
        }

        // Get item templates
        $itemTemplates = ItemTemplate::whereIn('id', $itemIds->unique()->filter())
            ->get()
            ->keyBy('id');

        return view('users.inventory', compact('user', 'inventory', 'box', 'itemTemplates'));
    }

    /**
     * Show buff form for user items.
     */
    public function showBuffForm($id)
    {
        $user = GameUser::findOrFail($id);

        // Get player inventory and box data
        $playerInventory = PlayerInventory::where('playerId', $user->playerId)->first();
        $playerBox = PlayerBox::where('playerId', $user->playerId)->first();

        $inventoryItems = [];
        $boxItems = [];

        if ($playerInventory && $playerInventory->arrItemBag) {
            $inventoryItems = $playerInventory->parsed_items;
        }

        if ($playerBox && $playerBox->arrItemBox) {
            $boxItems = $playerBox->parsed_items;
        }

        // Get item templates for display
        $allItemIds = array_merge(
            array_column($inventoryItems, 'id'),
            array_column($boxItems, 'id')
        );
        $allItemIds = array_filter($allItemIds, function($id) { return $id > 0; });

        $itemTemplates = [];
        if (!empty($allItemIds)) {
            $itemTemplates = ItemTemplate::whereIn('id', $allItemIds)->get()->keyBy('id');
        }

        // Get available item options from database (all types)
        $itemOptions = ItemOptionTemplate::orderBy('id')
            ->get()
            ->keyBy('id');

        return view('users.buff', compact('user', 'inventoryItems', 'boxItems', 'itemTemplates', 'itemOptions'));
    }

    /**
     * Process buff items request.
     */
    public function buffItems(Request $request, $id)
    {
        $user = GameUser::findOrFail($id);

        // Check if this is add item request
        if ($request->action === 'add_item') {
            return $this->addNewItem($request, $user);
        }

        $request->validate([
            'location' => 'required|in:inventory,box',
            'slot' => 'required|integer|min:0',
            'buff_type' => 'required|in:upgrade,options,both',
            'upgrade_level' => 'nullable|integer|min:0|max:16',
            // Dynamic options validation
            'option_id' => 'nullable|array',
            'option_id.*' => 'integer|exists:ngocrong_data.itemoptiontemplate,id',
            'option_value' => 'nullable|array',
            'option_value.*' => 'integer|min:0',
            // Legacy options for backward compatibility
            'inventory_*_option_id' => 'nullable|array',
            'inventory_*_option_value' => 'nullable|array',
            'box_*_option_id' => 'nullable|array',
            'box_*_option_value' => 'nullable|array',
        ]);

        // Get player data
        if ($request->location === 'inventory') {
            $playerData = PlayerInventory::where('playerId', $user->playerId)->first();
            $itemsField = 'arrItemBag';
        } else {
            $playerData = PlayerBox::where('playerId', $user->playerId)->first();
            $itemsField = 'arrItemBox';
        }

        if (!$playerData || !$playerData->$itemsField) {
            return back()->with('error', 'Không tìm thấy dữ liệu túi đồ của player.');
        }

        // Parse current items
        $items = json_decode($playerData->$itemsField, true);
        $slot = $request->slot;

        if (!isset($items[$slot]) || empty($items[$slot])) {
            return back()->with('error', 'Không tìm thấy item ở slot này.');
        }

        $item = $items[$slot];

        // Buff enchants (upgrade level stored in enchants array at indices 24-29)
        if ($request->buff_type === 'upgrade' || $request->buff_type === 'both') {
            if ($request->upgrade_level !== null) {
                // Set first enchant slot (index 24) to upgrade level
                $item[24] = $request->upgrade_level;
            }
        }

        // Buff options (stored at index 18 as array of [optionId, value] pairs)
        if ($request->buff_type === 'options' || $request->buff_type === 'both') {
            $newOptions = [];

            // Process dynamic options from main form
            if ($request->has('option_id') && $request->has('option_value')) {
                $optionIds = $request->option_id;
                $optionValues = $request->option_value;

                for ($i = 0; $i < count($optionIds); $i++) {
                    if (!empty($optionIds[$i]) && !empty($optionValues[$i]) && $optionValues[$i] > 0) {
                        $newOptions[] = [(int)$optionIds[$i], (int)$optionValues[$i]];
                    }
                }
            }

            // Process dynamic options from inventory/box forms
            $location = $request->location;
            $slot = $request->slot;
            $optionIdKey = "{$location}_{$slot}_option_id";
            $optionValueKey = "{$location}_{$slot}_option_value";

            if ($request->has($optionIdKey) && $request->has($optionValueKey)) {
                $optionIds = $request->input($optionIdKey, []);
                $optionValues = $request->input($optionValueKey, []);

                for ($i = 0; $i < count($optionIds); $i++) {
                    if (!empty($optionIds[$i]) && !empty($optionValues[$i]) && $optionValues[$i] > 0) {
                        $newOptions[] = [(int)$optionIds[$i], (int)$optionValues[$i]];
                    }
                }
            }

            $item[18] = $newOptions;
        }

        // Update the item in the array
        $items[$slot] = $item;

        // Save back to database
        $playerData->$itemsField = json_encode($items);
        $playerData->save();

        return back()->with('success', 'Đã buff item thành công!');
    }

    /**
     * Add new item to player inventory or box.
     */
    private function addNewItem(Request $request, $user)
    {
        $request->validate([
            'location' => 'required|in:inventory,box',
            'item_id' => 'required|integer|min:1',
            'quantity' => 'required|integer|min:1|max:999',
            'upgrade_level' => 'nullable|integer|min:0|max:16',
            // Dynamic options validation
            'option_id' => 'nullable|array',
            'option_id.*' => 'integer|exists:ngocrong_data.itemoptiontemplate,id',
            'option_value' => 'nullable|array',
            'option_value.*' => 'integer|min:0',
        ]);

        // Get player data
        if ($request->location === 'inventory') {
            $playerData = PlayerInventory::where('playerId', $user->playerId)->first();
            $itemsField = 'arrItemBag';
            $maxSlots = 50;
        } else {
            $playerData = PlayerBox::where('playerId', $user->playerId)->first();
            $itemsField = 'arrItemBox';
            $maxSlots = 100;
        }

        if (!$playerData) {
            return back()->with('error', 'Không tìm thấy dữ liệu túi đồ của player.');
        }

        // Parse current items
        $items = json_decode($playerData->$itemsField, true) ?? [];

        // Find empty slot
        $emptySlot = null;
        for ($i = 0; $i < $maxSlots; $i++) {
            if (!isset($items[$i]) || empty($items[$i]) || (is_array($items[$i]) && ($items[$i][0] ?? 0) <= 0)) {
                $emptySlot = $i;
                break;
            }
        }

        if ($emptySlot === null) {
            return back()->with('error', 'Không có slot trống để thêm item.');
        }

        // Verify item exists in template
        $itemTemplate = ItemTemplate::find($request->item_id);
        if (!$itemTemplate) {
            return back()->with('error', 'Item ID không tồn tại trong database.');
        }

        // Create options array from dynamic inputs
        $options = [];
        if ($request->has('option_id') && $request->has('option_value')) {
            $optionIds = $request->option_id;
            $optionValues = $request->option_value;

            for ($i = 0; $i < count($optionIds); $i++) {
                if (!empty($optionIds[$i]) && !empty($optionValues[$i]) && $optionValues[$i] > 0) {
                    $options[] = [(int)$optionIds[$i], (int)$optionValues[$i]];
                }
            }
        }

        // Create new item with 30-parameter structure
        $newItem = [
            (int)$request->item_id,           // 0: itemId
            $emptySlot,                       // 1: slot
            $itemTemplate->type ?? 3,         // 2: type
            (int)$request->quantity,          // 3: quantity
            false,                            // 4: isLock
            0, 0, 0, 0, 0,                   // 5-9: damage, defense, critical, hp, mp
            0, 0, 0, 0,                      // 10-13: stats
            -1, -1,                          // 14-15: params
            false, false,                     // 16-17: isEquipped, isNew
            $options,                         // 18: options array
            "", "", "",                       // 19-21: strings
            0, false,                         // 22-23: param3, isSpecial
            (int)($request->upgrade_level ?? 0), // 24: enchant1 (upgrade level)
            -1, -1, -1, -1, -1               // 25-29: enchants 2-6
        ];

        // Add item to array
        $items[$emptySlot] = $newItem;

        // Save back to database
        $playerData->$itemsField = json_encode($items);
        $playerData->save();

        $locationText = $request->location === 'inventory' ? 'hành trang' : 'rương đồ';
        return back()->with('success', "Đã thêm {$itemTemplate->name} vào {$locationText} slot {$emptySlot}!");
    }

    /**
     * Get items by planet for AJAX requests.
     */
    public function getItemsByPlanet($planet)
    {
        // Validate planet parameter
        if (!in_array($planet, ['0', '1', '2', '3'])) {
            return response()->json(['error' => 'Invalid planet'], 400);
        }

        // Map planet to gender values
        $genderMap = [
            '0' => 0, // Trái Đất
            '1' => 1, // Namek
            '2' => 2, // Xayda
            '3' => 3, // Items khác
        ];

        $gender = $genderMap[$planet];

        // Get items by gender (planet)
        $items = ItemTemplate::where('gender', $gender)
            ->orderByDesc('id')
            ->get(['id', 'name', 'type', 'level'])
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'type' => $item->type,
                    'level' => $item->level,
                    'display_text' => "ID: {$item->id} - {$item->name} (Lv.{$item->level})"
                ];
            });

        return response()->json($items);
    }

    /**
     * Get item options for AJAX requests.
     */
    public function getItemOptions()
    {
        $options = ItemOptionTemplate::orderBy('id')
            ->get(['id', 'name', 'type'])
            ->map(function ($option) {
                return [
                    'id' => $option->id,
                    'name' => $option->name,
                    'type' => $option->type,
                    'display_name' => str_replace('#', '', $option->name) // Remove # placeholder for display
                ];
            });

        return response()->json($options);
    }

    /**
     * Show dedicated buff page for a specific item.
     */
    public function showItemBuffPage($userId, $location, $slot)
    {
        $user = GameUser::findOrFail($userId);

        // Validate location
        if (!in_array($location, ['inventory', 'box'])) {
            return back()->with('error', 'Vị trí không hợp lệ.');
        }

        // Get player data
        if ($location === 'inventory') {
            $playerData = PlayerInventory::where('playerId', $user->playerId)->first();
            $itemsField = 'arrItemBag';
        } else {
            $playerData = PlayerBox::where('playerId', $user->playerId)->first();
            $itemsField = 'arrItemBox';
        }

        if (!$playerData || !$playerData->$itemsField) {
            return back()->with('error', 'Không tìm thấy dữ liệu túi đồ của player.');
        }

        // Parse items and get specific item
        $items = json_decode($playerData->$itemsField, true);

        if (!isset($items[$slot]) || empty($items[$slot])) {
            return back()->with('error', 'Không tìm thấy item ở slot này.');
        }

        $itemData = $items[$slot];

        // Parse item using model method
        if ($location === 'inventory') {
            $parsedItems = $playerData->parsedItems;
        } else {
            $parsedItems = $playerData->parsedItems;
        }

        $item = null;
        foreach ($parsedItems as $parsedItem) {
            if ($parsedItem['slot'] == $slot) {
                $item = $parsedItem;
                break;
            }
        }

        if (!$item) {
            return back()->with('error', 'Không thể parse item data.');
        }

        // Get item template
        $itemTemplate = ItemTemplate::find($item['id']);

        // Get available item options from database (all types)
        $itemOptions = ItemOptionTemplate::orderBy('id')
            ->get()
            ->keyBy('id');

        return view('users.buff-item', compact('user', 'item', 'itemTemplate', 'itemOptions', 'location', 'slot'));
    }

    /**
     * Update specific item via dedicated buff page.
     */
    public function updateItemBuff(Request $request, $userId, $location, $slot)
    {
        $user = GameUser::findOrFail($userId);

        $request->validate([
            'upgrade_level' => 'nullable|integer|min:0|max:16',
            'option_id' => 'nullable|array',
            'option_id.*' => 'integer|exists:ngocrong_data.itemoptiontemplate,id',
            'option_value' => 'nullable|array',
            'option_value.*' => 'integer|min:0',
        ]);

        // Get player data
        if ($location === 'inventory') {
            $playerData = PlayerInventory::where('playerId', $user->playerId)->first();
            $itemsField = 'arrItemBag';
        } else {
            $playerData = PlayerBox::where('playerId', $user->playerId)->first();
            $itemsField = 'arrItemBox';
        }

        if (!$playerData || !$playerData->$itemsField) {
            return back()->with('error', 'Không tìm thấy dữ liệu túi đồ của player.');
        }

        // Parse current items
        $items = json_decode($playerData->$itemsField, true);

        if (!isset($items[$slot]) || empty($items[$slot])) {
            return back()->with('error', 'Không tìm thấy item ở slot này.');
        }

        $item = $items[$slot];

        // Update upgrade level (stored in enchants array at index 24)
        if ($request->upgrade_level !== null) {
            $item[24] = $request->upgrade_level;
        }

        // Update options (stored at index 18 as array of [optionId, value] pairs)
        $newOptions = [];
        if ($request->has('option_id') && $request->has('option_value')) {
            $optionIds = $request->option_id;
            $optionValues = $request->option_value;

            for ($i = 0; $i < count($optionIds); $i++) {
                if (!is_null($optionIds[$i]) && !empty($optionValues[$i]) && $optionValues[$i] > 0) {
                    $newOptions[] = [(int)$optionIds[$i], (int)$optionValues[$i]];
                }
            }
        }
        $item[18] = $newOptions;

        // Update the item in the array
        $items[$slot] = $item;

        // Save back to database
        $playerData->$itemsField = json_encode($items);
        $playerData->save();

        return back()->with('success', 'Đã cập nhật item thành công!');
    }
}
