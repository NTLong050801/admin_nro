<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OptionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Management Routes
    Route::resource('users', UserController::class)->except(['create', 'store']);
    Route::post('/users/{user}/toggle-ban', [UserController::class, 'toggleBan'])->name('users.toggle-ban');
    Route::post('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
    Route::get('/users/{user}/inventory', [UserController::class, 'inventory'])->name('users.inventory');
    Route::get('/users/{user}/buff', [UserController::class, 'showBuffForm'])->name('users.buff');
    Route::post('/users/{user}/buff', [UserController::class, 'buffItems'])->name('users.buff.store');

    // Dedicated item buff page
    Route::get('/users/{user}/buff/{location}/{slot}', [UserController::class, 'showItemBuffPage'])->name('users.buff.item');
    Route::post('/users/{user}/buff/{location}/{slot}', [UserController::class, 'updateItemBuff'])->name('users.buff.item.update');

    // AJAX routes for item selection
    Route::get('/api/items-by-planet/{planet}', [UserController::class, 'getItemsByPlanet'])->name('api.items-by-planet');
    Route::get('/api/item-options', [UserController::class, 'getItemOptions'])->name('api.item-options');

    // Option Management Routes
    Route::resource('options', OptionController::class)->only(['index', 'show', 'edit', 'update']);
    Route::post('/options/{option}/reset-stats', [OptionController::class, 'resetStats'])->name('options.reset-stats');
    Route::post('/options/{option}/teleport', [OptionController::class, 'teleport'])->name('options.teleport');
    Route::post('/options/{option}/update-tasks', [OptionController::class, 'updateTasks'])->name('options.update-tasks');
});

require __DIR__.'/auth.php';
