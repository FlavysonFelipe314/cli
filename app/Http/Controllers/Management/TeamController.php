<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Mail\TeamInvitationMail;
use App\Models\TeamMember;
use App\Models\TeamInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $query = TeamMember::where('user_id', Auth::id())
            ->with('member')
            ->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        if ($request->has('employment_type') && $request->employment_type) {
            $query->where('employment_type', $request->employment_type);
        }
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        $members = $query->get();
        $invitations = TeamInvitation::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->get();

        $summary = [
            'total' => $members->count(),
            'active' => $members->where('status', 'active')->count(),
            'pending_invites' => $invitations->count(),
        ];

        return view('management.team.index', compact('members', 'invitations', 'summary'));
    }

    public function store(Request $request)
    {
        // Verificar se o usuário tem plano ativo
        if (!Auth::user()->hasActiveSubscription()) {
            return redirect()->route('subscriptions.index')
                ->with('error', 'Você precisa de um plano ativo para adicionar membros à equipe.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'position' => 'nullable|string|max:255',
            'employment_type' => 'required|in:CLT,PJ,Freelancer,Estagiário',
            'entry_date' => 'required|date',
            'estimated_monthly_cost' => 'nullable|numeric|min:0',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        $member = TeamMember::create($validated);

        // Criar convite
        $invitation = TeamInvitation::create([
            'user_id' => Auth::id(),
            'team_member_id' => $member->id,
            'email' => $validated['email'],
            'status' => 'pending',
        ]);

        // Enviar email de convite
        try {
            Mail::to($validated['email'])->send(new TeamInvitationMail($invitation));
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar convite de equipe: ' . $e->getMessage());
        }

        return redirect()->route('management.team.index')
            ->with('success', 'Membro adicionado e convite enviado com sucesso!');
    }

    public function update(Request $request, TeamMember $teamMember)
    {
        Gate::authorize('update', $teamMember);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'position' => 'nullable|string|max:255',
            'employment_type' => 'required|in:CLT,PJ,Freelancer,Estagiário',
            'entry_date' => 'required|date',
            'estimated_monthly_cost' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,pending',
        ]);

        $teamMember->update($validated);

        return redirect()->route('management.team.index')
            ->with('success', 'Membro atualizado com sucesso!');
    }

    public function destroy(TeamMember $teamMember)
    {
        Gate::authorize('delete', $teamMember);
        $teamMember->delete();
        
        return redirect()->route('management.team.index')
            ->with('success', 'Membro removido com sucesso!');
    }
}
