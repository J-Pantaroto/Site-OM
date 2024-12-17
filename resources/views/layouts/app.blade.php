<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ mix('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ mix('css/bootstrap.min.css') }}">
    <title>{{ config('app.name', 'Mercado das Latas') }}</title>
    <link rel="icon" href="{{ config('config.imgs.fav_icon_path') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @include('colors')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased"
    data-exibir-preco="{{ config('config.config.exibir_preco') === 'S' ? 'true' : 'false' }}">
    <div id="rota" data-id={{ Route::currentRouteName() }}></div>
    <div id="background" class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')
        @isset($header)
            <header id="banner" class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset
        <main>
            {{ $slot }}
        </main>
    </div>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="{{ mix('js/dashboard.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
</body>

</html>
