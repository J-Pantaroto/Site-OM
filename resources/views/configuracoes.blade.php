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
            <div id="banner" class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="GET" action="{{ route('configuracoes') }}" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" placeholder="Buscar configuração..." value="{{ request('search') }}" class="form-control">
                        <button type="submit" class="btn btn-primary button-primary">Pesquisar</button>
                    </div>
                </form>

                <div class="accordion mb-4" id="accordionConfiguracoes">

                    @if($cores->isNotEmpty())
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingColors">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseColors" aria-expanded="true" aria-controls="collapseColors">
                                    Configurações de Cores
                                </button>
                            </h2>
                            <div id="collapseColors" class="accordion-collapse collapse show" aria-labelledby="headingColors" data-bs-parent="#accordionConfiguracoes">
                                <div class="accordion-body">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Variável</th>
                                                <th>Valor Atual</th>
                                                <th>Ação</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cores as $key => $cor)
                                            <tr>
                                                <td>{{ is_array($cor) ? $cor['key'] ?? $key : $key }}</td>
                                                <td style="background-color: {{ is_array($cor) ? $cor['value'] ?? '' : $cor }}">{{ is_array($cor) ? $cor['value'] ?? '' : $cor }}</td>
                                                <td><a href="{{ route('configuracoes.edit', is_array($cor) ? $cor['key'] ?? $key : $key) }}" class="btn btn-outline-dark">Editar</a></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($imagens->isNotEmpty())
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingImages">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseImages" aria-expanded="false" aria-controls="collapseImages">
                                    Configurações de Imagens
                                </button>
                            </h2>
                            <div id="collapseImages" class="accordion-collapse collapse" aria-labelledby="headingImages" data-bs-parent="#accordionConfiguracoes">
                                <div class="accordion-body">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Variável</th>
                                                <th>Valor Atual</th>
                                                <th>Ação</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($imagens as $imagem)
                                                <tr>
                                                    <td>{{ $imagem['key'] }}</td>
                                                    <td><img src="{{ asset($imagem['value']) }}" alt="{{ $imagem['key'] }}" width="50" height="50"></td>
                                                    <td><a href="{{ route('configuracoes.edit', $imagem['key']) }}" class="btn btn-outline-dark">Editar</a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($configuracoes->isNotEmpty())
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingText">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseText" aria-expanded="false" aria-controls="collapseText">
                                    Configurações de configuracao
                                </button>
                            </h2>
                            <div id="collapseText" class="accordion-collapse collapse" aria-labelledby="headingText" data-bs-parent="#accordionConfiguracoes">
                                <div class="accordion-body">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Variável</th>
                                                <th>Valor Atual</th>
                                                <th>Ação</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($configuracoes as $key => $configuracao)
                                            <tr>
                                                <td>{{ is_array($configuracao) ? $configuracao['key'] ?? $key : $key }}</td>
                                                <td>{{ is_array($configuracao) ? $configuracao['value'] ?? '' : $configuracao }}</td>
                                                <td><a href="{{ route('configuracoes.edit', is_array($configuracao) ? $configuracao['key'] ?? $key : $key) }}" class="btn btn-outline-dark">Editar</a></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
