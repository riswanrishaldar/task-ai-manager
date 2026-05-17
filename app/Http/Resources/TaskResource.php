<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'title' => $this->title,
            'description' => $this->description,

            'priority' => $this->priority,
            'status' => $this->status,

            'due_date' => $this->due_date?->toDateString(),

            'assigned_to' => $this->assigned_to,

            'assignee' => $this->whenLoaded('assignee', function () {
                return [
                    'id' => $this->assignee?->id,
                    'name' => $this->assignee?->name,
                    'email' => $this->assignee?->email,
                ];
            }),

            'ai_summary' => $this->ai_summary,
            'ai_priority' => $this->ai_priority,

            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}