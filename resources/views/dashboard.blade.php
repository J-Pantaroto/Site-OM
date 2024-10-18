<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Painel') }}
        </h2>
    </x-slot>

    <!-- Painel de Boas Vindas -->
    <div class="py-12">
        <div class="container">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4 text-center">
                <h3 class="fs-3 font-semibold text-gray-800">Minhas compras</h3>
                <div class="text-gray-900 dark:text-gray-100">
                    {{ __("Bem vindo! " . explode(' ', Auth::user()->name)[0]) }}
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Compras -->
    <div class="py-12">
        <div class="container">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Compra</th>
                            <th scope="col">Produto</th>
                            <th scope="col">Quantidade</th>
                            <th scope="col">Data da Compra</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vendas as $venda)
                            @foreach($venda->itensVenda as $item)
                            <tr>
                                <th scope="row">{{ $venda->id }}</th>
                                <td>{{ $item->produto->nome }}</td>
                                <td>{{ $item->quantidade }}</td>
                                <td>{{ $venda->data_venda }}</td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>