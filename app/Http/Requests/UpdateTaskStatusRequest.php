<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\TaskStatusEnum;

class UpdateTaskStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $task = $this->route('task');

        //return $this->user()->can('updateStatus', $task);
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in(TaskStatusEnum::values()),
            ],
        ];
    }

    /**
     * Optional: custom validation messages
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Task status is required.',
            'status.in' => 'The selected status is invalid.',
        ];
    }
}