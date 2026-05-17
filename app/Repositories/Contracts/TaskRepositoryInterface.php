<?php

namespace App\Repositories\Contracts;

use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TaskRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 10): LengthAwarePaginator;

    public function paginateForUser(
        User $user,
        array $filters = [],
        int $perPage = 10
    ): LengthAwarePaginator;

    public function findOrFail(int $id): Task;

    public function findForUserOrFail(User $user, int $id): Task;

    public function create(array $data): Task;

    public function update(int $id, array $data): Task;

    public function updateStatus(int $id, string $status): Task;

    public function delete(int $id): bool;

    public function getDashboardStats(?User $user = null): array;
}