<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\TaskPriorityEnum;
use App\Enums\TaskStatusEnum;


class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $task = $this->route('task');

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],

            'description' => ['nullable', 'string'],

            'priority' => [
                'required',
                Rule::in(TaskPriorityEnum::values()),
            ],

            'status' => [
                'required',
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

    /**
     * Optional: custom messages (you can remove if not needed)
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The task title is required.',
            'priority.in' => 'The selected priority is invalid.',
            'status.in' => 'The selected status is invalid.',
            'assigned_to.exists' => 'The selected user does not exist.',
        ];
    }
}