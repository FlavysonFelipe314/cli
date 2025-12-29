<?php

namespace App\Policies;

use App\Models\TaskColumn;
use App\Models\User;

class TaskColumnPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, TaskColumn $taskColumn): bool
    {
        return $user->id === $taskColumn->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, TaskColumn $taskColumn): bool
    {
        return $user->id === $taskColumn->user_id;
    }

    public function delete(User $user, TaskColumn $taskColumn): bool
    {
        return $user->id === $taskColumn->user_id;
    }
}
