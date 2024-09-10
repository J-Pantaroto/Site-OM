@props(['active' => false])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-4 py-2 border border-yellow-400 rounded-full bg-gray-100 dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-4 py-2 border border-transparent rounded-full bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-700 transition duration-150 ease-in-out';
@endphp

<div class="relative max-w-sm mx-auto"> <!-- Define o limite máximo de largura -->
    <input 
        type="text" 
        {{ $attributes->merge(['class' => $classes . ' w-full text-gray-900 dark:text-gray-300']) }} 
        placeholder="{{ $slot ?? __('Search...') }}" 
    />
    <button type="submit" class="absolute right-0 top-0 mt-2 mr-4 text-gray-600 dark:text-gray-400 hover:text-yellow-700 focus:text-yellow-700 transition">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 16.5a6 6 0 100-12 6 6 0 000 12z" />
        </svg>
    </button>
</div>
