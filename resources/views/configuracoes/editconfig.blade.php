<x-app-layout>
    <x-slot name="header">
        <h2 id="banner-text" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Configuração') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    {{ __('Editar Configuração') }}: {{ $configuracao }}
                </h3>
                <form action="{{ route('configuracoes.update', $configuracao) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @if ($tipo === 'cor')
                        <div class="mb-4 flex items-center space-x-2">
                            <label for="value" class="form-label">Cor</label>
                            <div class="flex items-center w-full">
                                <!-- Input de texto -->
                                <input type="text" name="value" id="value" class="form-control flex-grow"
                                    value="{{ old('value', $value) }}" required>

                                <!-- Input de cor -->
                                <input style="border-color: #6b7280;" type="color"
                                    class="form-control form-control-color ml-2" id="colorPicker" name="value"
                                    value="{{ old('value', $value) }}">
                            </div>
                        </div>
                    @endif
                    @if ($tipo === 'imagem')
                        <div class="mb-4">
                            <label for="value" class="form-label">Carregar Nova Imagem</label>
                            <input type="file" name="value" id="value" class="form-control" accept="image/*">

                            @if (!empty($value))
                                <div class="mt-4">
                                    <p>Imagem Atual:</p>
                                    <img src="{{ asset($value) }}" alt="Imagem atual"
                                        style="max-width: 200px; display: block; margin-top: 10px;">
                                </div>
                            @endif
                        </div>
                    @endif
                    @if ($tipo === 'geral')
                        <div class="mb-4">
                            <label for="value" class="form-label">Valor</label>
                            <input type="text" name="value" id="value" class="form-control"
                                value="{{ old('value', $value) }}" required>
                        </div>
                    @endif
                    <x-primary-button>{{ __('Salvar') }}</x-primary-button>
                    <button type="button" class="btn btn-primary button-danger"
                        onclick="window.location='{{ route('configuracoes') }}'">Cancelar</button>

                </form>

            </div>
        </div>
    </div>

</x-app-layout>
