@include('colors')
<style>
    /* BOTOES */
    .btn.button-danger {
        background-color: var(--color-danger);
        border-color: var(--color-danger);
        color: var(--color-white-90);
    }

    .btn.button-danger:hover {
        background-color: var(--color-danger-hover);
        border-color: var(--color-danger-hover);
        color: var(--color-white);
    }

    .btn.button-danger:active {
        background-color: var(--color-danger-hover) !important;
        border-color: var(--color-danger-hover) !important;
        color: var(--color-white) !important;
    }

    .btn.button-danger:focus {
        background-color: var(--color-danger-hover) !important;
        border-color: var(--color-danger-hover) !important;
        color: var(--color-white) !important;
    }

    .btn.button-primary {
        background-color: var(--color-button-primary);
        border-color: var(--color-button-primary);
        color: var(--color-black-90);
    }

    .btn.button-primary:hover {
        background-color: var(--color-button-primary-hover);
        border-color: var(--color-button-primary-hover);
        color: var(--color-black);
    }

    .btn.button-primary:focus {
        background-color: var(--color-button-primary-hover) !important;
        border-color: var(--color-button-primary-hover) !important;
        color: var(--color-black) !important;
    }

    .btn.button-primary:active {
        background-color: var(--color-button-primary-hover) !important;
        border-color: var(--color-button-primary-hover) !important;
        color: var(--color-black) !important;
    }

    #logo-login {
        width: 3rem;
        height: 3rem;
        filter: drop-shadow(0 0 4px var(--color-white-90));
        transition: all 0.3s ease;
    }

    #logo-login:hover {
        transform: scale(1.2);
        filter: drop-shadow(0 0 4px var(--color-danger));
    }

    #footer-email {
        text-align: center;
        padding: 10px;
        background-color: var(--color-footer-background);
        color: var(--color-white-90);
    }

    #header-email {
        background-color: var(--color-header);
        color: var(--color-header-text);
        text-align: left;
    }

    #corpo-email {
        padding: 20px;
        background-color: var(--color-card-produto-background);
        color: var(--color-card-produto-text);
    }
</style>
<x-mail::layout>
    <x-slot:header>
        <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
            <div id="header-email">
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current" />
                </a>
            </div>
    </x-slot:header>

    <div id="corpo-email">
        @yield('content')
    </div>

    <x-slot:footer>
        <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
            <div id="footer-email">
                © {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.
            </div>
        </table>
    </x-slot:footer>
</x-mail::layout>
