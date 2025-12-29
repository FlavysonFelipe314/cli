<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Super admin e admin sempre têm acesso
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return $next($request);
        }

        // Verificar se tem assinatura ativa
        if (!$user->hasActiveSubscription()) {
            return redirect()->route('subscriptions.index')
                ->with('error', 'Você precisa de uma assinatura ativa para acessar esta funcionalidade.');
        }

        return $next($request);
    }
}
