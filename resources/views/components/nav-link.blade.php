@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-4 py-2 rounded-full bg-gray-900 text-white text-sm font-bold transition'
            : 'inline-flex items-center px-4 py-2 rounded-full bg-gray-200 text-gray-800 hover:bg-gray-900 hover:text-white text-sm font-semibold transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>