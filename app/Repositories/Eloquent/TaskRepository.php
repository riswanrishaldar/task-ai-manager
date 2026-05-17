<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Enums\RoleEnum;
use App\Enums\TaskPriorityEnum;
use App\Enums\TaskStatusEnum;
use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskRepository implements TaskRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return Task::query()
            ->with('assignee:id,name,email')
            ->filter($filters)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function paginateForUser(User $user, array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Task::query()
            ->with('assignee:id,name,email')
            ->filter($filters)
            ->latest();

        if ($user->role !== RoleEnum::ADMIN->value) {
            $query->where('assigned_to', $user->id);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function findOrFail(int $id): Task
    {
        return Task::query()
            ->with('assignee:id,name,email')
            ->findOrFail($id);
    }

    public function findForUserOrFail(User $user, int $id): Task
    {
        $query = Task::query()
            ->with('assignee:id,name,email')
            ->whereKey($id);

        if ($user->role !== RoleEnum::ADMIN->value) {
            $query->where('assigned_to', $user->id);
        }

        return $query->firstOrFail();
    }

    public function create(array $data): Task
    {
        return Task::query()->create($data);
    }

    public function update(int $id, array $data): Task
    {
        $task = $this->findOrFail($id);
        $task->update($data);

        return $task->fresh('assignee');
    }

    public function updateStatus(int $id, string $status): Task
    {
        return $this->update($id, ['status' => $status]);
    }

    public function delete(int $id): bool
    {
        return $this->findOrFail($id)->delete();
    }

    public function getDashboardStats(?User $user = null): array
    {
        $query = Task::query();

        if ($user && $user->role !== RoleEnum::ADMIN->value) {
            $query->where('assigned_to', $user->id);
        }

        return [
            'total_tasks' => (clone $query)->count(),
            'completed_tasks' => (clone $query)->where('status', TaskStatusEnum::COMPLETED->value)->count(),
            'pending_tasks' => (clone $query)->where('status', TaskStatusEnum::PENDING->value)->count(),
            'high_priority_tasks' => (clone $query)->where('priority', TaskPriorityEnum::HIGH->value)->count(),
        ];
    }
}