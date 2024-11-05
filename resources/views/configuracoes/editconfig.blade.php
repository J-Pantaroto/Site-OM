<x-app-layout>
    <x-slot name="header">
        <h2 id="banner-text" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Configuração') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Editar Configuração') }}: {{ $configuracao }}</h3>
                <form action="{{ route('configuracoes.update', $configuracao) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="value" class="form-label">Valor</label>
                        <input type="text" name="value" id="value" class="form-control" value="{{ old('value', $value) }}" required>
                    </div>

                    <x-primary-button>{{ __('Salvar') }}</x-primary-button>
                    <x-secondary-button>
                        <a href="{{ route('configuracoes') }}">{{ __('Cancelar') }}</a>
                    </x-secondary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
