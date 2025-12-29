<?php

namespace App\Policies;

use App\Models\TeamMember;
use App\Models\User;

class TeamMemberPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, TeamMember $teamMember): bool
    {
        return $user->id === $teamMember->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, TeamMember $teamMember): bool
    {
        return $user->id === $teamMember->user_id;
    }

    public function delete(User $user, TeamMember $teamMember): bool
    {
        return $user->id === $teamMember->user_id;
    }
}
