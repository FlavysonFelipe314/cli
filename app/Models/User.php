<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'asaas_customer_id',
        'cpf_cnpj',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
    
    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class)->where('status', 'active')->latest();
    }

    public function activeSubscription(): ?Subscription
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where(function($q) {
                $q->whereNull('ends_at')
                  ->orWhere('ends_at', '>=', now());
            })
            ->latest()
            ->first();
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }

    public function hasActiveSubscription(): bool
    {
        $subscription = $this->activeSubscription();
        return $subscription && $subscription->isActive();
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(TeamMember::class, 'team_user', 'user_id', 'team_member_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function teamInvitations(): HasMany
    {
        return $this->hasMany(TeamInvitation::class, 'email', 'email')
            ->where('status', 'pending');
    }

    public function userModules(): HasMany
    {
        return $this->hasMany(UserModule::class);
    }

    /**
     * Verifica se o usuário tem acesso a um módulo específico
     */
    public function hasModuleAccess(string $moduleSlug): bool
    {
        // Super admin sempre tem acesso
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Verificar se o módulo está incluído no plano ativo
        $subscription = $this->activeSubscription();
        if ($subscription && $subscription->plan) {
            $allowedModules = $subscription->plan->allowed_modules ?? [];
            if (in_array($moduleSlug, $allowedModules)) {
                return true;
            }
        }

        // Verificar se o usuário comprou o módulo separadamente
        $userModule = $this->userModules()
            ->whereHas('module', function($q) use ($moduleSlug) {
                $q->where('slug', $moduleSlug);
            })
            ->where('status', 'active')
            ->first();

        if ($userModule && $userModule->isActive()) {
            return true;
        }

        return false;
    }

    /**
     * Retorna todos os módulos acessíveis pelo usuário
     */
    public function getAccessibleModules(): array
    {
        $modules = [];

        // Módulos do plano
        $subscription = $this->activeSubscription();
        if ($subscription && $subscription->plan) {
            $allowedModules = $subscription->plan->allowed_modules ?? [];
            $modules = array_merge($modules, $allowedModules);
        }

        // Módulos comprados separadamente
        $userModules = $this->userModules()
            ->where('status', 'active')
            ->with('module')
            ->get();

        foreach ($userModules as $userModule) {
            if ($userModule->isActive() && $userModule->module) {
                $modules[] = $userModule->module->slug;
            }
        }

        return array_unique($modules);
    }
}
