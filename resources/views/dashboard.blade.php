<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    #address-warning {
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
<x-app-layout>
    <x-slot name="header">
        <h2 id="banner-text" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Painel') }}
        </h2>
    </x-slot>
    @if ($showAddressWarning)
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
    
        Toast.fire({
            icon: "warning",
            html: `
                <p><strong>Atenção:</strong> Seu endereço ainda não está completo.</p>
                <p>
                    Para facilitar futuros pedidos, recomendamos preencher essas informações em
                    <a href="{{ route('profile.edit') }}" class="text-blue-500 underline">seu perfil</a>.
                </p>
            `
        });
    </script>
    
    @endif
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
