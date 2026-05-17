<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use App\Enums\RoleEnum;

class TaskPolicy
{
    public function view(User $user, Task $task): bool
    {
        return $user->role === RoleEnum::ADMIN->value
            || $task->assigned_to === $user->id;
    }

    public function update(User $user, Task $task): bool
    {
        return $user->role === RoleEnum::ADMIN->value
            || $task->assigned_to === $user->id;
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->role === RoleEnum::ADMIN->value;
    }

    public function updateStatus(User $user, Task $task): bool
    {
        return $user->role === RoleEnum::ADMIN->value
            || $task->assigned_to === $user->id;
    }

    public function create(User $user): bool
    {
        return in_array(
            $user->role,
            [
                RoleEnum::ADMIN->value,
                RoleEnum::USER->value
            ],
            true
        );
    }
}