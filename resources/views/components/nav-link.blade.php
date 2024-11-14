@props(['active'])

@php
$classes = ($active ?? false)
            ? 'text-decoration-none inline-flex items-center px-1 pt-1 border-b-2 border-yellow-400 dark:border-yellow-600 text-sm font-medium leading-5 text-gray-400 focus:outline-none focus:border-yellow-700 transition duration-150 ease-in-out'
            : 'text-decoration-none inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-white hover:text-yellow-700 hover:border-gray-300 focus:outline-none focus:text-yellow-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
