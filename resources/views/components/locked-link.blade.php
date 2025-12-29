@props(['route', 'icon', 'text', 'isLocked' => false])

@if($isLocked)
<a href="{{ route('subscriptions.index') }}" onclick="event.preventDefault(); window.location.href='{{ route('subscriptions.index') }}';" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors opacity-60 cursor-pointer" style="background-color: rgba(var(--primary), 0.05);">
    {!! $icon !!}
    <span style="color: rgb(var(--text-secondary));">{{ $text }}</span>
    <svg class="w-4 h-4 ml-auto" style="color: rgb(var(--text-secondary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
    </svg>
</a>
@else
<a href="{{ $route }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs(str_replace('.index', '.*', $route)) ? '' : 'hover:bg-opacity-50' }}" style="{{ request()->routeIs(str_replace('.index', '.*', $route)) ? 'background-color: rgb(var(--primary)); color: white;' : 'background-color: rgba(var(--primary), 0.1);' }}">
    {!! $icon !!}
    <span>{{ $text }}</span>
</a>
@endif

