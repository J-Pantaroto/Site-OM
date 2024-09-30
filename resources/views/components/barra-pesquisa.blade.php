@props(['active' => false])

@php
$classes = ($active ?? false)
            ? 'form-control border border-yellow-400 rounded bg-gray-100 dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:border-yellow-400 focus:ring-0 transition duration-150 ease-in-out'
            : 'form-control border border-transparent rounded bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:border-yellow-400 focus:ring-0 transition duration-150 ease-in-out';
@endphp

<form class="d-flex" role="search" method="POST">
    <div class="input-group w-50 mx-auto">
        <input
            type="text"
            name="pesquisa"
            {{ $attributes->merge(['class' => $classes . ' text-gray-900 dark:text-gray-300']) }}
            placeholder="{{ $slot ?? __('Search...') }}"
        />
        <input type="hidden" name="escopo" id="escopo" value="{{ Request::path() }}">
        <button type="submit" id="pesquisar">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 16.5a6 6 0 100-12 6 6 0 000 12z" />
            </svg>
        </button>
    </div>
</form>
