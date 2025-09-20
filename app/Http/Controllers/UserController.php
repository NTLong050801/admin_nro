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
            $itemIds = $itemIds->merge($inventory->parsedItems->pluck('id'));
        }

        if ($box && $box->parsedItems) {
            $itemIds = $itemIds->merge($box->parsedItems->pluck('id'));
        }

        // Get item templates
        $itemTemplates = ItemTemplate::whereIn('id', $itemIds->unique()->filter())
            ->get()
            ->keyBy('id');

        return view('users.inventory', compact('user', 'inventory', 'box', 'itemTemplates'));
    }
}
