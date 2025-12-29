<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('finance.accounts.index');
        }
        return view('auth.login');
    }

    /**
     * Handle a login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Se houver token de convite, processar
            if ($request->filled('invitation_token')) {
                $invitationController = new \App\Http\Controllers\TeamInvitationController();
                $invitation = \App\Models\TeamInvitation::where('token', $request->invitation_token)
                    ->where('status', 'pending')
                    ->where('email', Auth::user()->email)
                    ->first();
                
                if ($invitation) {
                    return $invitationController->processInvitation($invitation);
                }
            }
            
            // Redirecionar usuários sem plano para equipes
            if (!Auth::user()->hasActiveSubscription()) {
                return redirect()->intended(route('team.teams'));
            }
            
            return redirect()->intended(route('finance.accounts.index'));
        }

        throw ValidationException::withMessages([
            'email' => ['As credenciais fornecidas estão incorretas.'],
        ]);
    }

    /**
     * Handle a logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
