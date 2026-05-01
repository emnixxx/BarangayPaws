@php
    $type = strtolower($type ?? '');
    $cls  = 'pet-avatar';
    if ($type === 'dog') $cls .= ' pet-avatar-dog';
    elseif ($type === 'cat') $cls .= ' pet-avatar-cat';
    else $cls .= ' pet-avatar-other';
@endphp
<div class="{{ $cls }}">
    @if($type === 'dog')
        {{-- Dog icon --}}
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10 5l-2-3-3 3v3l-2 1v3l3 1v8h12v-8l3-1v-3l-2-1V5l-3-3-2 3"/>
            <circle cx="9.5" cy="13" r="0.7" fill="currentColor"/>
            <circle cx="14.5" cy="13" r="0.7" fill="currentColor"/>
            <path d="M11 16h2"/>
        </svg>
    @elseif($type === 'cat')
        {{-- Cat icon --}}
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 4l3 5h10l3-5v9a8 8 0 0 1-16 0V4z"/>
            <circle cx="9" cy="12" r="0.8" fill="currentColor"/>
            <circle cx="15" cy="12" r="0.8" fill="currentColor"/>
            <path d="M12 14l-1 1.5h2L12 14z" fill="currentColor"/>
            <path d="M9 17c1 .8 5 .8 6 0"/>
        </svg>
    @else
        {{-- Generic paw --}}
        <svg viewBox="0 0 24 24" fill="currentColor">
            <ellipse cx="7" cy="9" rx="2" ry="2.5"/>
            <ellipse cx="12" cy="6" rx="2" ry="2.5"/>
            <ellipse cx="17" cy="9" rx="2" ry="2.5"/>
            <ellipse cx="12" cy="15" rx="4" ry="3.5"/>
        </svg>
    @endif
</div>
