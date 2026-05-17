<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request (single-action controller).
     */
    public function __invoke(Request $request, TaskService $taskService)
    {
        return Inertia::render('Dashboard/Index', [
            'stats' => $taskService->dashboardStats($request->user()),
        ]);
    }

    public function getDashboardStats(?User $user = null): array
{
    $cacheKey = 'dashboard_stats_' . ($user?->id ?? 'guest');

    return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($user) {
        $query = Task::query();

        if ($user && $user->role !== RoleEnum::ADMIN->value) {
            $query->where('assigned_to', $user->id);
        }

        return [
            'total_tasks' => (clone $query)->count(),
            'completed_tasks' => (clone $query)->where('status', 'completed')->count(),
            'pending_tasks' => (clone $query)->where('status', 'pending')->count(),
            'high_priority_tasks' => (clone $query)->where('priority', 'high')->count(),
        ];
    });
}
}