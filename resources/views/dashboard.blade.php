<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Painel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <h3 class="p-2 fs-3 font-semibold text-gray-800 text-center">Minhas compras</h3>
                <div class="p-1 text-gray-900 dark:text-gray-100">
                    {{ __("Bem vindo! " . explode(' ', Auth::user()->name)[0]) }}
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <table class="table">
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
</x-app-layout>
