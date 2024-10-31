<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ mix('css/bootstrap.min.css') }}">

    @if (Route::currentRouteName() === 'home')
        <link rel="stylesheet" href="{{ mix('css/home.css') }}">
    @elseif (Route::currentRouteName() === 'produto/')
        <link rel="stylesheet" href="{{ asset('css/produto.css') }}">
    @endif

    <link rel="shortcut icon" href="imgs/login24.jpg" type="">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>{{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
</head>

<body class="d-flex flex-column min-vh-100 ">
    <div id="usuario-autenticado" data-autenticado="{{ auth()->check() ? 'true' : 'false' }}"></div>
    <nav class="navbar navbar-expand-lg shadow fixed-top">
        <div class="container-fluid align-items-center">
            <div class="navbar-collapse" id="navbarSupportedContent">
                <!-- Logo -->
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current"/>
                </a>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                </ul>
                @if (Route::currentRouteName() === 'home')
                    <form class="d-flex" role="search" method="POST" action="{{ url('/search') }}">
                        @csrf
                        <input class="form-control me-2" name="pesquisa" id="pesquisa" type="search"
                            placeholder="O que você procura ?" aria-label="Search">
                        <button type="submit" id="search-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ffffff"
                                class="bi bi-search" viewBox="0 0 16 16">
                                <path
                                    d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"
                                    stroke="#ffffff" stroke-width="1" />
                            </svg>
                        </button>
                    </form>
                @endif
                <div class="ms-3">
                    @if (Route::has('login'))
                            <div class="d-flex">
                                @auth
                                        <div class="dropdown d-flex align-items-center" onmouseenter="abrirFecharDropDown('enter')"
                                            onmouseleave="abrirFecharDropDown('leave')">
                                            <a class="dropdown-toggle d-flex align-items-center user-button" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false"
                                                title="Olá! {{ explode(' ', Auth::user()->name)[0] }}">
                                                <p class="mb-0">{{ __("Olá! " . explode(' ', Auth::user()->name)[0]) }}</p>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="white"
                                                    class="bi bi-person-circle ms-2" viewBox="0 0 16 16">
                                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                                    <path fill-rule="evenodd"
                                                        d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                                </svg>
                                            </a>
                                            <ul id="drop" class="dropdown-menu dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ url('/dashboard') }}">DashBoard</a></li>
                                                <li>
                                                    <form method="POST" action="{{ route('logout') }}">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item" style="cursor: pointer;">
                                                            Sair
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>

                                        </div>
                                    </div>
                                @else
                                    <div class="d-flex">
                                        <div class="dropdown d-flex align-items-center" onmouseenter="abrirFecharDropDown('enter')"
                                            onmouseleave="abrirFecharDropDown('leave')">
                                            <a class="dropdown-toggle d-flex align-items-center user-button" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="white"
                                                    class="bi bi-person-circle ms-2" viewBox="0 0 16 16">
                                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                                    <path fill-rule="evenodd"
                                                        d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                                </svg>
                                            </a>
                                            <ul id="drop" class="dropdown-menu dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="{{ route('login') }}">Entrar</a></li>
                                                <li>
                                                    @if (Route::has('register'))
                                                        <a href="{{ route('register') }}" class="dropdown-item" style="cursor: pointer;">
                                                            Solicitar acesso
                                                        </a>
                                                    @endif
                                                </li>
                                            </ul>

                                        </div>
                                    </div>
                                    </a>
                                @endauth
                        </div>
                    @endif
            </div>
        </div>
    </nav>
    <main class="flex-fill">
        {{ $slot }}
    </main>
    <footer class="mt-auto">
        <div id="footer_copyright">
            <div id="footer_social_media">
                <a href="{{ config('social.instagram') }}" target="_blank" class="footer-link" id="instagram">
                    <i class="fa-brands fa-instagram"></i>
                </a>
                <a href="{{ config('social.facebook') }}" target="_blank" class="footer-link" id="facebook">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>
                <a href="{{ config('social.whatsapp') }}" target="_blank" class="footer-link"
                    id="whatsapp">
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
            </div>
            <p id="texto-copyright">
                &#169; {{ config('app.name') }} ® 2024 - Todos os direitos reservados | Site criado por:
                <a id="link-footer" href="http://omturbo.com">
                    <img id="imagemTelaMaior" src="{{ asset('images/logoOM.png') }}" alt="OM Turbo">
                    <p class="d-none" id="textoTelaMenor">OM Informática</p>
                </a>
            </p>
        </div>
    </footer>
    <script src="{{ mix('js/app.js') }}"></script>
</body>

</html>