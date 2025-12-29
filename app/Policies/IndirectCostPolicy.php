<?php

namespace App\Policies;

use App\Models\IndirectCost;
use App\Models\User;

class IndirectCostPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, IndirectCost $indirectCost): bool
    {
        return $user->id === $indirectCost->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, IndirectCost $indirectCost): bool
    {
        return $user->id === $indirectCost->user_id;
    }

    public function delete(User $user, IndirectCost $indirectCost): bool
    {
        return $user->id === $indirectCost->user_id;
    }
}
