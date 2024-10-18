<x-guest-layout>
    <div id="rota" data-id={{ (Route::currentRouteName()) }}></div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <!-- Exibir mensagem de reenvio de e-mail de verificação -->
    @if (session('resentLink'))
        <div class="alert alert-info">
            <p>Não recebeu o e-mail de verificação?</p>
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-link">Clique aqui para reenviar o e-mail de verificação</button>
            </form>
        </div>
    @endif

    <!-- Mensagem de sucesso após o reenvio -->
    @if (session('status') === 'verification-link-sent')
        <div class="alert alert-success">
            O link de verificação foi reenviado para seu endereço de e-mail.
        </div>
    @endif


    <!-- Formulário de Login -->
    <form id="form" method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email') ?? session('email')" max="50" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <div id="erro-email"></div>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Senha')" />
            <div class="input-group">
                <x-text-input id="password" maxlength="15" class="block mt-1 w-full focus:border-yellow-500"
                    type="password" name="password" required autocomplete="new-password" />
                <span class="input-group-text toggle-password" id="ocultar" style="display:none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-eye-fill" viewBox="0 0 16 16">
                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                        <path
                            d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                    </svg>
                </span>
                <div id="erro-senha"></div>
            </div>
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-yellow-600 shadow-sm focus:ring-yellow-500 dark:focus:ring-yellow-600 dark:focus:ring-offset-gray-800"
                    name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Link para Esqueceu a senha e botão de login -->
        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 dark:focus:ring-offset-gray-800"
                    href="{{ route('password.request') }}">
                    {{ __('Esqueceu sua senha?') }}
                </a>
            @endif

            <x-primary-button id="login" class="ms-3">
                {{ __('Entrar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>