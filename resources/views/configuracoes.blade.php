<x-app-layout>
    <x-slot name="header">
        <h2 id="banner-text" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Configurações') }}
        </h2>
    </x-slot>
    
    @if (session('success'))
        <div id="success-message" style="display: none;">{{ session('success') }}</div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <!-- Barra de Pesquisa -->
                <form method="GET" action="{{ route('configuracoes') }}" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" placeholder="Buscar configuração..."
                            value="{{ request('search') }}" class="form-control">
                        <button type="submit" class="btn btn-outline-primary">Pesquisar</button>
                    </div>
                </form>

                <!-- Tabela de config -->
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Variável</th>
                            <th>Valor Atual</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($configuracoes as $configuracao)
                            <tr>
                                <td>{{ $configuracao['key'] }}</td>
                                <td
                                    style="background-color: {{ $configuracao['value'] }}; color: {{ $configuracao['value'] == '#ffffff' ? '#000' : '#fff' }}">
                                    {{ $configuracao['value'] }}</td>
                                <td>
                                    <a href="{{ route('configuracoes.edit', $configuracao['key']) }}"
                                        class="btn btn-outline-dark">Editar</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">Nenhuma configuração encontrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
