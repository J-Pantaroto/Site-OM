<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Painel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Bem vindo! " . explode(' ', Auth::user()->name)[0]) }}
                </div>
            </div>
        </div>
    </div>
    <h3 class="fs-3 font-semibold text-gray-800 text-center">Minhas compras</h3>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <table class="table">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Compra</th>
                        <th scope="col">Produto</th>
                        <th scope="col">Descricao</th>
                        <th scope="col">Data da Compra</th>
                        <th scope="col">Quantidade</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                        <td>@m2o</td>
                    </tr>
                    <tr>
                        <th scope="row">2</th>
                        <td>Jacob</td>
                        <td>Thornton</td>
                        <td>@fat</td>
                        <td>@f1t</td>
                    </tr>
                    <tr>
                        <th scope="row">3</th>
                        <td>Larry the Bird</td>
                        <td>Bird</td>
                        <td>@twitter</td>
                        <td>@insta</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>