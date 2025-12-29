@extends('layouts.app')

@section('title', 'Gerenciar Usuários - CLIVUS')
@section('page-title', 'Gerenciar Usuários')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold">Usuários</h2>
        <a href="{{ route('admin.users.create') }}" class="px-4 py-2 rounded-lg font-medium text-white transition-all" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
            Novo Usuário
        </a>
    </div>

    <div class="rounded-xl overflow-hidden" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr style="background-color: rgb(var(--bg-secondary));">
                        <th class="text-left py-3 px-4 text-sm font-semibold">Nome</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold">Email</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold">Plano</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold">Role</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="border-b" style="border-color: rgb(var(--border));">
                        <td class="py-3 px-4">{{ $user->name }}</td>
                        <td class="py-3 px-4">{{ $user->email }}</td>
                        <td class="py-3 px-4">
                            @php
                                $activeSub = $user->subscriptions->where('status', 'active')->where(function($s) {
                                    return !$s->ends_at || $s->ends_at->isFuture();
                                })->first();
                            @endphp
                            @if($activeSub && $activeSub->plan)
                            <span class="px-2 py-1 rounded text-xs" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                                {{ $activeSub->plan->name }}
                            </span>
                            @else
                            <span class="text-sm" style="color: rgb(var(--text-secondary));">Sem plano</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded text-xs capitalize" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="p-2 rounded-lg transition-colors" style="background-color: rgba(var(--primary), 0.1);">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t" style="border-color: rgb(var(--border));">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection

