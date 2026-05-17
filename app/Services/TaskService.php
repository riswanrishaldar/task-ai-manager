<?php

namespace App\Services;

use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Repositories\Eloquent\TaskRepository;

use App\Jobs\GenerateTaskAISummaryJob;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TaskService
{
    public function __construct(
        protected TaskRepositoryInterface $tasks,
    ) {}

    public function store(array $data): Task
    {
        return DB::transaction(function () use ($data) {
            $task = $this->tasks->create($data);

            GenerateTaskAISummaryJob::dispatch($task->id);

            return $task;
        });
    }

    public function update(Task $task, array $data): Task
    {
        return DB::transaction(function () use ($task, $data) {
            $updated = $this->tasks->update($task->id, $data);

            GenerateTaskAISummaryJob::dispatch($updated->id);

            return $updated;
        });
    }

    public function updateStatus(Task $task, string $status): Task
    {
        return $this->tasks->updateStatus($task->id, $status);
    }

    public function delete(Task $task): bool
    {
        return $this->tasks->delete($task->id);
    }

    public function dashboardStats(User $user): array
    {
        return $this->tasks->getDashboardStats($user);
    }
}