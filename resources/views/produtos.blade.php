<x-app-layout>
    <x-slot name="header">
        <h2 id="banner-text" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Produtos') }}
        </h2>
    </x-slot>

    @if (session('success'))
        <div id="success-message" style="display: none;">{{ session('success') }}</div>
    @endif
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="banner" class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <x-barra-pesquisa name="pesquisa">
                    {{ __('Digite para pesquisar um produto...') }}
                </x-barra-pesquisa>
                <div class="table-responsive mt-4">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th class="tablebackground" scope="col">Codigo</th>
                                <th class="tablebackground" scope="col">Produto</th>
                                <th class="tablebackground" scope="col">Nome</th>
                                @if (config('config.config.exibir_preco') === 'S')
                                    <th class="tablebackground" scope="col">Preço</th>
                                @endif
                                @if (config('config.config.validar_estoque') === 'S')
                                <th class="tablebackground" scope="col">Estoque</th>
                            @endif
                                <th class="tablebackground" scope="col">Inativo</th>
                                <th class="tablebackground" scope="col">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($produtos->isEmpty())
                                <tr>
                                    <td colspan="6">Nenhum produto cadastrado.</td>
                                </tr>
                            @else
                                @foreach ($produtos as $produto)
                                    <tr>
                                        <th class="{{ $produto->inativo === 'S' ? 'produto-inativo' : '' }}" scope="row">{{ $produto->codigo }}</th>
                                        <td class="{{ $produto->inativo === 'S' ? 'produto-inativo' : '' }}">
                                            @php
                                                $imagemPrincipal = $produto
                                                    ->imagens()
                                                    ->where('principal', true)
                                                    ->first();
                                            @endphp
                                            @if ($imagemPrincipal)
                                                <img src="{{ asset('storage/' . $imagemPrincipal->imagem) }}"
                                                    alt="Imagem do Produto" style="width: 8rem; height: auto;">
                                            @else
                                                <img src="{{ asset('storage/produtos/placeholder.png') }}"
                                                    alt="Imagem Placeholder" style="width: 8rem; height: auto;">
                                            @endif
                                        </td>
                                        <td class="{{ $produto->inativo === 'S' ? 'produto-inativo' : '' }}">
                                            {{ $produto->nome }}
                                            @if ($produto->inativo === 'S')
                                                <span id="inativo-icone" class="text-danger ms-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                        height="20" fill="currentColor" class="bi bi-info-circle"
                                                        viewBox="0 0 20 20">
                                                        <title>Este produto está inativo.</title>
                                                        <path
                                                            d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                                        <path
                                                            d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0" />
                                                    </svg>
                                                </span>
                                            </td>
                                            @endif
                                        @if (config('config.config.exibir_preco') === 'S')
                                            <td class="{{ $produto->inativo === 'S' ? 'produto-inativo' : '' }}">
                                                R$ {{ $produto->preco }}
                                            </td>
                                        @endif
                                        @if (config('config.config.validar_estoque') === 'S')
                                        <td class="{{ $produto->inativo === 'S' ? 'produto-inativo' : '' }}">
                                             Quantidade disponível: {{$produto->quantidade}}</p>
                                        </td>
                                        @endif
                                        <td>{{$produto->inativo}}</td>
                                        <td>
                                            <a type="button" class="btn btn-outline-dark"
                                                href="{{ route('produtos.edit', $produto->id) }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="dark" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path
                                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd"
                                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                </svg>
                                            </a>
                                            <form style="display:inline" action="{{ route('produtos.inative', $produto->id) }}" method="POST"
                                                onsubmit="return confirm('Tem certeza que deseja {{ $produto->inativo === 'S' ? 'ativar' : 'inativar' }} este produto?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-outline-danger">
                                                    @if ($produto->inativo === 'S')
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green" class="bi bi-check-circle" viewBox="0 0 16 16">
                                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                                        <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
                                                      </svg>
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" class="bi bi-x-circle"
                                                            viewBox="0 0 16 16">
                                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                                            <path d="M11.854 4.146a.5.5 0 0 1 0 .708L8.707 8l3.147 3.146a.5.5 0 0 1-.708.708L8 8.707l-3.146 3.147a.5.5 0 0 1-.708-.708L7.293 8 4.146 4.854a.5.5 0 1 1 .708-.708L8 7.293l3.146-3.147a.5.5 0 0 1 .708 0" />
                                                        </svg>
                                                    @endif
                                                </button>
                                            </form>
                                            
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div id="paginacao" class="d-flex justify-content-end">
                    {{ $produtos->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
