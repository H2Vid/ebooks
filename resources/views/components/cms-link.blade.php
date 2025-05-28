@props(['route', 'icon' => ''])

@php
    $active = request()->routeIs($route);
@endphp

<a href="{{ route($route) }}"
   {{ $attributes->merge([
        'class' => ($active
            ? 'bg-gray-100 font-semibold'
            : 'hover:bg-gray-50')
            . ' flex items-center gap-2 px-6 py-2 text-sm transition'
   ]) }}>
    <span>{{ $icon }}</span>
    <span>{{ $slot }}</span>
</a>
