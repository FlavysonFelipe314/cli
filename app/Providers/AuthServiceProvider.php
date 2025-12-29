<?php

namespace App\Providers;

use App\Models\EmployeeCostProfile;
use App\Models\FiscalObligation;
use App\Models\Task;
use App\Models\TaskColumn;
use App\Models\TeamMember;
use App\Policies\EmployeeCostProfilePolicy;
use App\Policies\FiscalObligationPolicy;
use App\Policies\TaskPolicy;
use App\Policies\TaskColumnPolicy;
use App\Policies\TeamMemberPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        EmployeeCostProfile::class => EmployeeCostProfilePolicy::class,
        FiscalObligation::class => FiscalObligationPolicy::class,
        TeamMember::class => TeamMemberPolicy::class,
        Task::class => TaskPolicy::class,
        TaskColumn::class => TaskColumnPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
