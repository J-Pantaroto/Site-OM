<x-app-layout>
    <x-slot name="header">
        <h2 id="banner-text" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Painel') }}
        </h2>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="banner" class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">

                <div class="text-center mb-4"> 
                    <h3 id="banner-text" class="fs-3 font-semibold text-gray-800">Minhas compras</h3>
                    <div id="banner-text" class="text-gray-900 dark:text-gray-100">
                        {{ __('Bem-vindo! ' . explode(' ', Auth::user()->name)[0]) }}
                    </div>
                </div>
                <div class="table-responsive mt-4">
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th class="tablebackground" scope="col">Compra</th>
                                <th class="tablebackground" scope="col">Produto</th>
                                <th class="tablebackground" scope="col">Quantidade</th>
                                <th class="tablebackground" scope="col">Data da Compra</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vendas as $venda)
                                @foreach ($venda->itensVenda as $item)
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
    </div>
</x-app-layout>
