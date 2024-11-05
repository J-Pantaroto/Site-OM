<x-app-layout>
    <x-slot name="header">
        <h2 id="banner-text" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Produto') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Formulário de Edição de Produto -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form method="POST" enctype="multipart/form-data"
                        action="{{ route('produtos.update', $produto->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <img src="{{ old('nome', $produto->imagem) }}" alt=""><br>
                            <label for="imagem" class="form-label">Nova imagem</label>
                            <input type="file" class="form-control" name="imagem" id="imagem">
                        </div>
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do Produto</label>
                            <input type="text" class="form-control" id="nome" name="nome"
                                value="{{ old('nome', $produto->nome) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao"
                                required>{{ old('descricao', $produto->descricao) }}</textarea>
                        </div>
                        <!-- Botão para salvar alterações -->
                        <x-primary-button>{{ __('Salvar') }}</x-primary-button>
                        <x-secondary-button>
                            <a href="{{ route('produtos') }}">{{ __('Cancelar') }}</a>
                        </x-secondary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>