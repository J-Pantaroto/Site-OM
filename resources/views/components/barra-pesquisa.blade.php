@props(['active' => false])

@php
$classes = ($active ?? false)
            ? 'form-control border border-yellow-400 rounded bg-gray-100 dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:border-yellow-400 focus:ring-0 transition duration-150 ease-in-out'
            : 'form-control border border-transparent rounded bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:border-yellow-400 focus:ring-0 transition duration-150 ease-in-out';
@endphp

<form class="mb-4" role="search" method="POST">
    <div class="input-group">
        <input
            type="text"
            name="pesquisa"
            {{ $attributes->merge(['class' => $classes . ' form-control']) }}
            placeholder="{{ $slot ?? __('Search...') }}"
        />
        <input type="hidden" name="escopo" id="escopo" value="{{ Request::path() }}">
        <button id="pesquisar" type="submit" class="btn btn-primary button-primary">Pesquisar</button>
    </div>
</form>
