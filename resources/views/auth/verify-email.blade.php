<x-guest-layout>

    @if (session('status') == 'Sucesso')
        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ __('Um novo link de verificação foi enviado para o endereço de e-mail que você forneceu durante o registro.') }}
        </div>
    @endif
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Obrigado por se inscrever! Antes de começar, você poderia verificar seu endereço de e-mail clicando no link que acabamos de enviar para você? Se você não recebeu o e-mail, teremos prazer em lhe enviar outro.
') }}
    </div>
    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <input type="hidden" name="emailValor" value="{{session('email')}}">
            <div>
                <x-primary-button>
                    {{ __('Reenviar email de verificação') }}
                </x-primary-button>
            </div>
        </form>
        @if (session('status') === 'Sucesso')
            <div class="alert alert-success">
                O link de verificação foi reenviado para seu endereço de e-mail.
            </div>
        @endif
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit"
                class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 dark:focus:ring-offset-gray-800">
                {{ __('Sair') }}
            </button>
        </form>
    </div>
</x-guest-layout>