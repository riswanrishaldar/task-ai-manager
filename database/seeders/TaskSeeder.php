<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->pluck('id')->toArray();

        $tasks = [
            [
                'title' => 'Implement repository contract',
                'description' => 'Create TaskRepositoryInterface and bind implementation in provider.',
                'priority' => 'high',
                'status' => 'pending',
                'due_date' => now()->addDays(2)->toDateString(),
                'ai_summary' => 'Repository layer setup pending for task module.',
                'ai_priority' => 'high',
            ],
            [
                'title' => 'Build task listing UI',
                'description' => 'Develop responsive task table with filters and badges.',
                'priority' => 'medium',
                'status' => 'inprogress',
                'due_date' => now()->addDays(3)->toDateString(),
                'ai_summary' => 'UI work has started and needs responsive polishing.',
                'ai_priority' => 'medium',
            ],
            [
                'title' => 'Add policy authorization',
                'description' => 'Restrict users to assigned tasks and allow admin full access.',
                'priority' => 'high',
                'status' => 'completed',
                'due_date' => now()->subDay()->toDateString(),
                'ai_summary' => 'Authorization rules implemented for admin and assigned users.',
                'ai_priority' => 'high',
            ],
            [
                'title' => 'Integrate AI summary service',
                'description' => 'Generate AI summary and AI priority using service layer.',
                'priority' => 'high',
                'status' => 'pending',
                'due_date' => now()->addDays(1)->toDateString(),
                'ai_summary' => 'AI integration still pending and critical for submission.',
                'ai_priority' => 'high',
            ],
            [
                'title' => 'Create API resource responses',
                'description' => 'Return consistent task payloads for API endpoints.',
                'priority' => 'low',
                'status' => 'completed',
                'due_date' => now()->subDays(2)->toDateString(),
                'ai_summary' => 'API formatting work is complete.',
                'ai_priority' => 'low',
            ],
        ];

        foreach ($tasks as $index => $task) {
            Task::create([
                'title' => $task['title'],
                'description' => $task['description'],
                'priority' => $task['priority'],
                'status' => $task['status'],
                'due_date' => $task['due_date'],
                'assigned_to' => $users[$index % count($users)],
                'ai_summary' => $task['ai_summary'],
                'ai_priority' => $task['ai_priority'],
            ]);
        }
    }
}