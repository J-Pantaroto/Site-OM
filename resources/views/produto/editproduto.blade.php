<x-app-layout>
    <x-slot name="header">
        <h2 id="banner-text" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Produto') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Fechar"></button>
                        </div>
                    @endif
                    <div class="mb-4">
                        <label class="form-label">Imagens Atuais</label>
                        <div class="row">
                            @foreach ($produto->imagens as $imagem)
                                <div class="col-md-3 mb-3">
                                    <div class="card">
                                        <img style="height: 80px;" src="{{ asset('storage/' . $imagem->imagem) }}"
                                            class="card-img-top" alt="Imagem do Produto">
                                        <input  class="form-check-input mt-2" type="radio" name="imagem_principal_selecionada"
                                            value="{{ $imagem->imagem }}"
                                            onclick="setImagemPrincipal('{{ $imagem->imagem }}')">
                                        <div class="card-body text-center">
                                            <form action="{{ route('produtos.imagens.destroy', $imagem->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Tem certeza que deseja remover esta imagem?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Remover</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <form method="POST" enctype="multipart/form-data"
                        action="{{ route('produtos.update', $produto->id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="imagem_principal" id="imagem_principal" value="">
                        <div class="mb-4">
                            <label for="imagens" class="form-label">Adicionar Novas Imagens</label>
                            <input type="file" class="form-control" name="imagens[]" id="imagens" multiple>
                            <small class="form-text text-muted">Você pode selecionar múltiplas imagens.</small>
                        </div>
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do Produto</label>
                            <input type="text" class="form-control @error('nome') is-invalid @enderror"
                                id="nome" name="nome" value="{{ old('nome', $produto->nome) }}" required>
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @if (config('config.config.exibir_preco') === 'S')
                        <div class="mb-3">
                            <label for="preco" class="form-label">Preço</label>
                            <input type="text" class="form-control @error('preco') is-invalid @enderror"
                                   id="preco" name="preco" value="{{ old('preco', $produto->preco) }}">
                            @error('preco')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @endif
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao" required>{{ old('descricao', $produto->descricao) }}</textarea>
                            @error('descricao')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">{{ __('Salvar') }}</button>
                            <a href="{{ route('produtos') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
            function setImagemPrincipal(imagem) {
            document.getElementById('imagem_principal').value = imagem;
        }
</script>
