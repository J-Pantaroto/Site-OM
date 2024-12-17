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
                    <a href="{{ route('profile.edit') }}" class="text-blue-500 underline">seu perfil</a>.
                </p>
            `
        });
    </script>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-center mb-4 font-semibold text-gray-800">Histórico de Compras</h3>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Data da Compra</th>
                                @if ($exibirPreco)
                                    <th>Total</th>
                                @endif
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($vendas->isEmpty())
                            <tr>
                                <td colspan="7">Nenhuma compra realizada ainda!</td>
                            </tr>
                            @endif
                            @foreach ($vendas as $venda)
                                <tr>
                                    <td>{{ $venda['id'] }}</td>
                                    <td>{{ $venda['data_venda'] }}</td>
                                    @if ($exibirPreco)
                                        <td>R$ {{ number_format($venda['total'], 2, ',', '.') }}</td>
                                    @endif
                                    <td>
                                        <button class="btn btn-primary button-primary" onclick="toggleDetalhes({{ $venda['id'] }})">
                                            Ver Detalhes
                                        </button>
                                    </td>
                                </tr>
                                <tr id="detalhes-{{ $venda['id'] }}" style="display: none;">
                                    <td colspan="4">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Produto</th>
                                                    <th>Quantidade</th>
                                                    @if ($exibirPreco)
                                                        <th>Preço</th>
                                                        <th>Subtotal</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($venda['itens'] as $item)
                                                    <tr>
                                                        <td>{{ $item['nome'] }}</td>
                                                        <td>{{ $item['quantidade'] }}</td>
                                                        @if ($exibirPreco)
                                                            <td>R$ {{ number_format($item['preco'], 2, ',', '.') }}</td>
                                                            <td>R$ {{ number_format($item['subtotal'], 2, ',', '.') }}</td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    function toggleDetalhes(vendaId) {
        const detalhes = document.getElementById(`detalhes-${vendaId}`);
        detalhes.style.display = detalhes.style.display === 'none' ? 'table-row' : 'none';
    }
</script>