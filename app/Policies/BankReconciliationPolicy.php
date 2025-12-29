<?php

namespace App\Policies;

use App\Models\BankReconciliation;
use App\Models\User;

class BankReconciliationPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, BankReconciliation $bankReconciliation): bool
    {
        return $user->id === $bankReconciliation->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, BankReconciliation $bankReconciliation): bool
    {
        return $user->id === $bankReconciliation->user_id;
    }

    public function delete(User $user, BankReconciliation $bankReconciliation): bool
    {
        return $user->id === $bankReconciliation->user_id;
    }
}
