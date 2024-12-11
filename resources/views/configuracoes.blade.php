<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Configurações') }}
        </h2>
    </x-slot>

    @if (session('success'))
        <div id="success-message" style="display: none;">{{ session('success') }}</div>
    @endif


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">

                <form method="GET" action="{{ route('configuracoes') }}" class="mb-4 d-flex align-items-center">
                    <div class="input-group me-2">
                        <input type="text" name="search" placeholder="Buscar configuração..."
                            value="{{ request('search') }}"
                            class="form-control border border-transparent rounded bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:border-yellow-400 focus:ring-0 transition duration-150 ease-in-out form-control">
                    </div>
                    <div class="me-2">
                        <select name="category" class="form-select">
                            <option value="">Todas as Categorias</option>
                            <option value="cores" {{ request('category') === 'cores' ? 'selected' : '' }}>Cores
                            </option>
                            <option value="imagens" {{ request('category') === 'imagens' ? 'selected' : '' }}>Imagens
                            </option>
                            <option value="gerais" {{ request('category') === 'gerais' ? 'selected' : '' }}>Gerais
                            </option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary button-primary">Filtrar</button>
                </form>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Categoria</th>
                            <th>Variável</th>
                            <th>Valor Atual</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($configuracoes as $key => $configuracao)
                            <tr>
                                <td>
                                    @if (Str::startsWith($key, 'COLOR_'))
                                        Cores
                                    @elseif (Str::startsWith($key, 'IMG_'))
                                        Imagens
                                    @else
                                        Gerais
                                    @endif
                                </td>
                                <td>{{ $key }}</td>
                                <td>
                                    @if (Str::startsWith($key, 'IMG_'))
                                        <img src="{{ asset($configuracao) }}" alt="Imagem" style="max-height: 50px;">
                                    @else
                                        {{ is_array($configuracao) ? $configuracao['value'] ?? '' : $configuracao }}
                                    @endif
                                </td>


                                <td>
                                    <a href="{{ route('configuracoes.edit', $key) }}"
                                        class="btn btn-outline-dark button-primary">Editar</a>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $configuracoes->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
