<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function showRegistrationForm(Request $request)
    {
        $invitationToken = $request->get('invitation_token');
        $invitationEmail = $request->get('invitation_email');
        $invitation = null;

        if ($invitationToken) {
            $invitation = TeamInvitation::where('token', $invitationToken)
                ->where('status', 'pending')
                ->first();
        }

        return view('auth.register', compact('invitation', 'invitationToken', 'invitationEmail'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'invitation_token' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Criar usuário (sem plano)
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'user',
            ]);

            // Se houver token de convite, processar
            if ($request->filled('invitation_token')) {
                $invitation = TeamInvitation::where('token', $request->invitation_token)
                    ->where('status', 'pending')
                    ->where('email', $validated['email'])
                    ->first();

                if ($invitation && (!$invitation->expires_at || $invitation->expires_at->isFuture())) {
                    // Atualizar status do convite
                    $invitation->update([
                        'status' => 'accepted',
                        'accepted_at' => now(),
                    ]);

                    // Atualizar status do membro
                    if ($invitation->teamMember) {
                        $invitation->teamMember->update([
                            'status' => 'active',
                            'member_user_id' => $user->id,
                        ]);

                        // Adicionar usuário à equipe
                        $invitation->teamMember->members()->attach($user->id, [
                            'role' => 'member',
                        ]);
                    }
                }
            }

            DB::commit();

            // Fazer login automático
            Auth::login($user);

            return redirect()->route('team.teams')
                ->with('success', 'Conta criada com sucesso!' . ($request->filled('invitation_token') ? ' Convite aceito automaticamente.' : ''));
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao registrar usuário: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Erro ao criar conta. Tente novamente.');
        }
    }
}
