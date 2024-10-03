<x-main-layout>
    <h3 class="h3 text-center">{{ $prod->nome }}</h3>
    <div class="container mt-5">
        <div class="row gx-0 shadow-lg mt-3 p-4"> <!-- Adicionando padding ao card -->
            <!-- Coluna para a imagem do produto -->
            <div class="col-4">
                <img src="{{$prod->imagem}}" class="card-img-top" alt="{{ $prod->nome }}">
            </div>
            <!-- Coluna para informações do produto -->
            <div class="col">
                <div class="d-flex flex-column h-100">
                    <div class="card-body">
                        <!-- Nome do Produto -->
                        <h3 class="card-title text-center">{{ $prod->nome }}</h3>

                        <!-- Descrição do Produto -->
                        <p class="card-text mt-4 p-4">{{ $prod->descricao }}</p>
                    </div>

                    <!-- Botão de Comprar, alinhado ao canto inferior direito -->
                    <div class="mt-auto d-flex justify-content-end"> <!-- Empurrando o botão para o canto inferior direito -->
                        <form>
                            @csrf
                            <button class="btn btn-primary btn-lg" type="submit">
                                <i class="fas fa-shopping-cart"></i> Comprar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if (Route::has('login'))
            @auth
                <div class="modal fade" id="modal-loja" tabindex="-1" aria-labelledby="modal-lojaLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="modal-lojaLabel">Carrinho</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <h2 class="text-center">Carrinho de Compras</h2>
                            <div class="modal-body">
                                <div class="container mt-5">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="tabelaCarrinho">
                                            <thead>
                                                <tr>
                                                    <th>Imagem</th>
                                                    <th>Produto</th>
                                                    <th>Quantidade</th>
                                                    <th>Ação</th>
                                                </tr>
                                            </thead>
                                            <tbody id="cartItems">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row justify-content-end">
                                        <div class="col-md-4">
                                            <h4>Total: R$ <span id="totalAmount">0.00</span></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                                <button type="button" class="btn btn-outline-warning ">Finalizar compra</button>
                            </div>
                        </div>
                    </div>
                </div>
                <a type="button" id="comprar-btn" data-bs-toggle="modal" data-bs-target="#modal-loja" class="floating-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <span id="cart-count">0</span>
                </a>
            @endauth
        @endif
        <script src="{{ mix('js/produto.js') }}"></script>
</x-main-layout>
