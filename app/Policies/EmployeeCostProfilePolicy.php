<?php

namespace App\Policies;

use App\Models\EmployeeCostProfile;
use App\Models\User;

class EmployeeCostProfilePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, EmployeeCostProfile $employeeCostProfile): bool
    {
        return $user->id === $employeeCostProfile->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, EmployeeCostProfile $employeeCostProfile): bool
    {
        return $user->id === $employeeCostProfile->user_id;
    }

    public function delete(User $user, EmployeeCostProfile $employeeCostProfile): bool
    {
        return $user->id === $employeeCostProfile->user_id;
    }
}
