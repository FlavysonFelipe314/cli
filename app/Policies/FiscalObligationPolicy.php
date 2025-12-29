<?php

namespace App\Policies;

use App\Models\FiscalObligation;
use App\Models\User;

class FiscalObligationPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, FiscalObligation $fiscalObligation): bool
    {
        return $user->id === $fiscalObligation->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, FiscalObligation $fiscalObligation): bool
    {
        return $user->id === $fiscalObligation->user_id;
    }

    public function delete(User $user, FiscalObligation $fiscalObligation): bool
    {
        return $user->id === $fiscalObligation->user_id;
    }
}
