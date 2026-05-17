<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Http\Resources\TaskResource;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(
        protected TaskRepositoryInterface $tasks,
        protected TaskService $taskService
    ) {}

    public function index(Request $request)
    {
        $tasks = $this->tasks->paginateForUser(
            $request->user(),
            $request->only([
                'search',
                'status',
                'priority',
                'assigned_to',
                'due_date_from',
                'due_date_to',
            ])
        );

        return TaskResource::collection($tasks);
    }

   
    public function store(StoreTaskRequest $request)
    {
        $task = $this->taskService->store($request->validated());

        return (new TaskResource($task->load('assignee')))
            ->response()
            ->setStatusCode(201);
    }

   
    public function show(Request $request, int $taskId)
    {
        $task = $this->tasks->findForUserOrFail($request->user(), $taskId);

        $this->authorize('view', $task);

        return new TaskResource($task->load('assignee'));
    }

   
    public function update(UpdateTaskRequest $request, int $taskId)
    {
        $task = $this->tasks->findForUserOrFail($request->user(), $taskId);

        $this->authorize('update', $task);

        $updated = $this->taskService->update($task, $request->validated());

        return new TaskResource($updated->load('assignee'));
    }

   
    public function updateStatus(UpdateTaskStatusRequest $request, int $taskId)
    {
        $task = $this->tasks->findForUserOrFail($request->user(), $taskId);

        $updated = $this->taskService->updateStatus(
            $task,
            $request->validated()['status']
        );

        return new TaskResource($updated->load('assignee'));
    }

   
    public function aiSummary(Request $request, int $taskId)
    {
        $task = $this->tasks->findForUserOrFail($request->user(), $taskId);

        $this->authorize('view', $task);

        return response()->json([
            'id' => $task->id,
            'ai_summary' => $task->ai_summary,
            'ai_priority' => $task->ai_priority,
        ]);
    }

  
    public function destroy(Request $request, int $taskId)
    {
        $task = $this->tasks->findForUserOrFail($request->user(), $taskId);

        $this->authorize('delete', $task);

        $this->taskService->delete($task);

        return response()->json(null, 204);
    }
}