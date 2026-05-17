<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Services\TaskService;
use App\Enums\TaskPriorityEnum;
use App\Enums\TaskStatusEnum;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\UpdateTaskStatusRequest;


class TaskPageController extends Controller
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

        return Inertia::render('Tasks/Index', [
            'tasks' => TaskResource::collection($tasks),
            'filters' => $request->only([
                'search',
                'status',
                'priority',
                'assigned_to',
                'due_date_from',
                'due_date_to',
            ]),
        ]);
    }

   
    public function create()
    {
        return Inertia::render('Tasks/Form', [
            'task' => null,
            'users' => User::query()
                ->select('id', 'name')
                ->get(),

            'priorities' => TaskPriorityEnum::values(),
            'statuses' => TaskStatusEnum::values(),
        ]);
    }

  
    public function store(StoreTaskRequest $request)
    {
        $task = $this->taskService->store($request->validated());

        return redirect()
            ->route('tasks.show', $task->id)
            ->with('success', 'Task created successfully.');
    }

 
    public function show(Request $request, int $taskId)
    {
        $task = $this->tasks->findForUserOrFail($request->user(), $taskId);

        $this->authorize('view', $task);

        return Inertia::render('Tasks/Show', [
           'task' => (new TaskResource(
           $task->load('assignee')
           ))->resolve(request()),
                                            ]);
    }

   
    public function edit(Request $request, int $taskId)
    {
        $task = $this->tasks->findForUserOrFail($request->user(), $taskId);

        $this->authorize('update', $task);

        return Inertia::render('Tasks/Form', [
            'task' => new TaskResource($task->load('assignee')),

            'users' => User::query()
                ->select('id', 'name')
                ->get(),

            'priorities' => TaskPriorityEnum::values(),
            'statuses' => TaskStatusEnum::values(),
        ]);
    }

   
    public function update(UpdateTaskRequest $request, int $taskId)
    {
        $task = $this->tasks->findForUserOrFail($request->user(), $taskId);

        $this->authorize('update', $task);

        $this->taskService->update($task, $request->validated());

        return redirect()
            ->route('tasks.show', $taskId)
            ->with('success', 'Task updated successfully.');
    }

    public function updateStatus(UpdateTaskStatusRequest $request, int $taskId)
{
    $task = $this->tasks->findForUserOrFail($request->user(), $taskId);

    $this->authorize('updateStatus', $task);

    $this->taskService->updateStatus($task, $request->validated()['status']);

    return redirect()
        ->back()
        ->with('success', 'Task status updated successfully.');
}
}