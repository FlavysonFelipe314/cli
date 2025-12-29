<?php

namespace App\Http\Controllers;

use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeamInvitationController extends Controller
{
    public function accept($token)
    {
        $invitation = TeamInvitation::where('token', $token)
            ->where('status', 'pending')
            ->firstOrFail();

        if ($invitation->expires_at && $invitation->expires_at->isPast()) {
            $invitation->update(['status' => 'expired']);
            return redirect()->route('login')
                ->with('error', 'Este convite expirou. Entre em contato com o responsável da equipe.');
        }

        // Verificar se o usuário está logado
        if (!Auth::check()) {
            // Verificar se já existe usuário com este email
            $user = User::where('email', $invitation->email)->first();
            
            if ($user) {
                // Redirecionar para login
                return redirect()->route('login')
                    ->with('message', 'Você já possui uma conta. Faça login para aceitar o convite.')
                    ->with('invitation_token', $token);
            } else {
                // Redirecionar para registro simples (sem plano)
                return redirect()->route('register', ['invitation_token' => $token, 'invitation_email' => $invitation->email])
                    ->with('message', 'Crie uma conta para aceitar o convite da equipe.');
            }
        }

        // Usuário está logado, aceitar convite
        return $this->processInvitation($invitation);
    }

    public function processInvitation(TeamInvitation $invitation)
    {
        $user = Auth::user();

        // Verificar se o email do convite corresponde ao email do usuário
        if ($user->email !== $invitation->email) {
            return redirect()->route('team.teams')
                ->with('error', 'Este convite não é para o seu email.');
        }

        DB::beginTransaction();
        try {
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

            DB::commit();

            return redirect()->route('team.teams')
                ->with('success', 'Convite aceito com sucesso! Você agora faz parte da equipe.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao aceitar convite: ' . $e->getMessage());
            
            return redirect()->route('team.teams')
                ->with('error', 'Erro ao aceitar convite. Tente novamente.');
        }
    }

    public function teams()
    {
        $user = Auth::user();
        
        // Equipes que o usuário faz parte
        $myTeams = $user->teams()
            ->with('owner')
            ->get();

        // Convites pendentes
        $pendingInvitations = TeamInvitation::where('email', $user->email)
            ->where('status', 'pending')
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', now());
            })
            ->with('owner', 'teamMember')
            ->get();

        return view('team.teams', compact('myTeams', 'pendingInvitations'));
    }
}
