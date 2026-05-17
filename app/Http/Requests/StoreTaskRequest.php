<?php

namespace App\Http\Requests;

use App\Enums\TaskPriorityEnum;
use App\Enums\TaskStatusEnum;
use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Task::class);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],

            'description' => ['nullable', 'string'],

            'priority' => [
                'required',
                'string',
                Rule::in(TaskPriorityEnum::values()),
            ],

            'status' => [
                'required',
                'string',
                Rule::in(TaskStatusEnum::values()),
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'assigned_to' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Task title is required.',
            'priority.in' => 'Invalid priority selected.',
            'status.in' => 'Invalid status selected.',
            'assigned_to.exists' => 'Selected user does not exist.',
        ];
    }
}