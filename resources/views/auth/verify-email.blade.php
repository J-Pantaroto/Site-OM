<x-guest-layout>
    <div id="rota" data-id={{ (Route::currentRouteName()) }}></div>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Obrigado por se inscrever! Antes de começar, você poderia verificar seu endereço de e-mail clicando no link que acabamos de enviar para você? Se você não recebeu o e-mail, teremos prazer em lhe enviar outro.') }}
    </div>

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button id="reenviar">
                {{ __('Reenviar email de verificação') }}
            </x-primary-button>
            <input type="hidden" name="emailValor" value="{{ session('email', old('emailValor')) }}">
        </form>

        <!-- Mensagem de sucesso caso o e-mail seja reenviado -->
        @if (session('status') === 'Sucesso')
            <div class="mt-4 font-medium text-sm text-green-600">
                {{ __('Um novo link de verificação foi enviado para seu e-mail.') }}
            </div>
        @endif
    </div>

    <div class="mt-4">
        <!-- Exibe o e-mail armazenado na sessão -->
        <p>Email registrado: <strong>{{ session('email') }}</strong></p>
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button  type="submit"
            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 dark:focus:ring-offset-gray-800">
            {{ __('Sair') }}
        </button>

    </form>
</x-guest-layout>