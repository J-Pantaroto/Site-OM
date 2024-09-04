<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ mix('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ mix('css/home.css') }}">
    <link rel="shortcut icon" href="imgs/login24.jpg" type="">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>{{ config('app.name', 'Mercado das Latas') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
</head>

<body>
    <nav class="navbar navbar-expand-lg shadow fixed-top">
        <div class="container-fluid align-items-center">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('images/logo.png') }}" width="50vh" class="rounded" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                </ul>
                <form class="d-flex" role="search" method="POST" action="{{ url('/search') }}">
                    @csrf
                    <input class="form-control me-2" name="pesquisa" id="pesquisa" type="search" placeholder="Pesquise um produto"
                        aria-label="Search">
                    <button type="submit" id="search-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ffffff"
                            class="bi bi-search" viewBox="0 0 16 16">
                            <path
                                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"
                                stroke="#ffffff" stroke-width="1"/>
                        </svg>
                    </button>
                </form>
                <div class="ms-3">
                    @if (Route::has('login'))
                        <div class="d-flex">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="btn btn-warning ms-2">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-warning ms-2">
                                    Entrar
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn btn-warning ms-2">
                                        Solicitar acesso
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>
    {{ $slot }}
    <footer>
        <div id="footer_copyright">
            <div id="footer_social_media">
                <a href="https://www.instagram.com/mercadodaslatas/" target="_blank" class="footer-link" id="instagram">
                    <i class="fa-brands fa-instagram"></i>
                </a>
                <a href="https://www.facebook.com/mercadodaslatas" target="_blank" class="footer-link" id="facebook">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>
                <a href="https://mercadodaslatas.my.canva.site/contatobalcao" target="_blank" class="footer-link"
                    id="whatsapp">
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
            </div>
            <p id="texto-copyright">
                &#169; Mercado das Latas ® 2024 - Todos os direitos reservados | Site criado por
                <a href="http://omturbo.com">
                    <img src="{{ asset('images/logoOM.png') }}" alt="OM Turbo">
                </a>
            </p>
        </div>
    </footer>
    <script src="{{ mix('js/app.js') }}"></script>

    <script src="{{ mix('js/bootstrap.min.js') }}"></script>
</body>

</html>