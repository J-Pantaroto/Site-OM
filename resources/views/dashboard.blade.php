<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Minhas Compras') }}
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
                    <a href="{{ route('profile.edit') }}" class="text-yellow-500 underline">seu perfil</a>.
                </p>
            `,
        });
    </script>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-lg">Pedidos em Andamento</h3>
                    <ul class="mt-4">
                        @forelse ($pedidosEmAndamento as $pedido)
                            <li class="border-b border-gray-200 py-2">
                                <div class="flex justify-between items-center">
                                    <span class="{{ $pedido['status'] === 'pendente' ? 'text-yellow-500' : ($pedido['status'] === 'liberado' ? 'text-blue-500' : 'text-green-500') }}">
                                        Pedido #{{ $pedido['id'] }} - {{ ucfirst($pedido['status']) }}
                                    </span>
                                    <span class="text-sm text-gray-500">{{ $pedido['data_venda'] }}</span>
                                </div>
                                <button onclick="toggleItems({{ $pedido['id'] }})" class="btn btn-primary btn-sm button-primary">Ver Itens</button>
                                <ul id="itens-{{ $pedido['id'] }}" class="hidden mt-2 text-sm text-gray-600">
                                    @foreach ($pedido['itens'] as $item)
                                        <li>
                                            {{ $item['nome'] }} ({{ $item['quantidade'] }}x)
                                            @if ($exibirPreco)
                                                - R$ {{ number_format($item['preco'], 2, ',', '.') }}
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @empty
                            <li class="text-gray-500">Nenhum pedido em andamento.</li>
                        @endforelse
                    </ul>

                    <h3 class="font-semibold text-lg mt-8">Últimas Compras</h3>
                    <ul class="mt-4">
                        @forelse ($ultimasCompras as $compra)
                            <li class="border-b border-gray-200 py-2">
                                <div class="flex justify-between items-center">
                                    <span class="{{ $compra['status'] === 'cancelado' ? 'text-red-500' : 'text-green-500' }}">
                                        Compra #{{ $compra['id'] }} - {{ ucfirst($compra['status']) }}
                                    </span>
                                    <span class="text-sm text-gray-500">{{ $compra['data_venda'] }}</span>
                                </div>
                                <button onclick="toggleItems({{ $compra['id'] }})" class="btn btn-primary btn-sm button-primary">Ver Itens</button>
                                <ul id="itens-{{ $compra['id'] }}" class="hidden mt-2 text-sm text-gray-600">
                                    @foreach ($compra['itens'] as $item)
                                        <li>
                                            {{ $item['nome'] }} ({{ $item['quantidade'] }}x)
                                            @if ($exibirPreco)
                                                - R$ {{ number_format($item['preco'], 2, ',', '.') }}
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @empty
                            <li class="text-gray-500">Nenhuma compra encontrada.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function toggleItems(id) {
        const itens = document.getElementById(`itens-${id}`);
        itens.classList.toggle('hidden');
    }
</script>
