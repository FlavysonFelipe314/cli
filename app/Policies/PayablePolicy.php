<?php

namespace App\Policies;

use App\Models\Payable;
use App\Models\User;

class PayablePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Payable $payable): bool
    {
        return $user->id === $payable->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Payable $payable): bool
    {
        return $user->id === $payable->user_id;
    }

    public function delete(User $user, Payable $payable): bool
    {
        return $user->id === $payable->user_id;
    }
}
