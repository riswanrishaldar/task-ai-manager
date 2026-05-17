<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\AuthController;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->group(function () {

    // Get all tasks
    Route::get('/tasks', [TaskController::class, 'index']);

    // Create task
    Route::post('/tasks', [TaskController::class, 'store']);

    // Get single task
    Route::get('/tasks/{task}', [TaskController::class, 'show']);

    // Update full task
    Route::put('/tasks/{task}', [TaskController::class, 'update']);

    // Delete task
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

    // Update only status
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus']);

    // AI summary endpoint
    Route::get('/tasks/{task}/ai-summary', [TaskController::class, 'aiSummary']);
});