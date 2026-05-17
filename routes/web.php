<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\TaskPageController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/', [TaskPageController::class, 'index'])->name('index');
        Route::get('/create', [TaskPageController::class, 'create'])->name('create');
        Route::post('/', [TaskPageController::class, 'store'])->name('store');
        Route::get('/{task}', [TaskPageController::class, 'show'])->name('show');
        Route::get('/{task}/edit', [TaskPageController::class, 'edit'])->name('edit');
        Route::put('/{task}', [TaskPageController::class, 'update'])->name('update');
        Route::patch('/{task}/status', [TaskPageController::class, 'updateStatus'])->name('updateStatus');
    });
});
require __DIR__.'/auth.php';


