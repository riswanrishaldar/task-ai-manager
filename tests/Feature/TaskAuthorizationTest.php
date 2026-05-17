<?php

namespace Tests\Feature\Task;

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_view_unassigned_task(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $otherUser = User::factory()->create(['role' => 'user']);

        $task = Task::factory()->create([
            'assigned_to' => $otherUser->id,
        ]);

        $this->actingAs($user)
            ->get(route('tasks.show', $task->id))
            ->assertForbidden();
    }
}