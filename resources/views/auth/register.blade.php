<x-guest-layout>
    <form method="POST" id="form" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
        <div id="rota" data-id={{ (Route::currentRouteName()) }}></div>
            <x-input-label for="name" :value="__('Nome')" />
            <x-text-input id="name" maxlength="40" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
            <div id="erro-nome"></div>
        </div>
        <!-- CPF/CNPJ -->
        <div class="mt-4">
            <x-input-label for="cpf_cnpj" :value="__('CPF/CNPJ')" />
            <x-text-input id="cpf_cnpj" maxlength="18" class="block mt-1 w-full" type="text" name="cpf_cnpj"
                :value="old('cpf_cnpj')" required autocomplete="cpf_cnpj" />
            <x-input-error :messages="$errors->get('cpf_cnpj')" class="mt-2" />
            <div id="erro-cpf-cnpj"></div>
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" maxlength="50" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <div id="erro-email"></div>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Senha')" />

            <div class="input-group">
                <x-text-input id="password" maxlength="15" class="block mt-1 w-full focus:border-yellow-500" type="password" name="password" required
                    autocomplete="new-password" />
                <span class="input-group-text toggle-password" id="ocultar" style="display:none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-eye-fill" viewBox="0 0 16 16">
                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                        <path
                            d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                    </svg>
                </span>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            <div id="erro-senha"></div>
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar Senha')" />
            <div class="input-group">
                <x-text-input id="password_confirmation" maxlength="15" class="block mt-1 w-full focus:border-yellow-500" type="password"
                    name="password_confirmation" required autocomplete="new-password" />
                <span class="input-group-text toggle-password" id="confirmar-ocultar" style="display:none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-eye-fill" viewBox="0 0 16 16">
                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                        <path
                            d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                    </svg>
                </span>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            <div id="erro-confirmar-senha"></div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                href="{{ route('login') }}">
                {{ __('Ja possui seu acesso ?') }}
            </a>

            <x-primary-button id="registrar" class="ms-4">
                {{ __('Registrar') }}
            </x-primary-button>
            <input type="hidden" name="emailValor" value="{{session('email')}}">
        </div>
    </form>
</x-guest-layout>