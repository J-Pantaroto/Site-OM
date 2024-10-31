<x-main-layout>
    <h3 class="h3 text-center">{{ $prod->nome }}</h3>
    <div class="container mt-5">
        <div class="row gx-0 shadow-lg mt-3 p-4">
            <div class="col-4">
                <img id="imagem" src="{{$prod->imagem}}" class="card-img-top" alt="{{ $prod->nome }}">
            </div>
            <div class="col">
                <div class="d-flex flex-column h-100">
                    <div class="card-body">
                        <h3 id="nome-produto" class="card-title text-center">{{ $prod->nome }}</h3>
                        <p class="card-text mt-4 p-4">{{ $prod->descricao }}</p>
                    </div>

                    <div class="mt-auto d-flex justify-content-end">
                        <a class="btn btn-warning btn-lg" id="adicionar-carrinho" data-id="{{ $prod->id }}" type="submit">
                            <i class="fas fa-shopping-cart"></i> Adicionar ao carrinho
                        </a>
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
                        <div class="mt-auto d-flex justify-content-end">
                            <button id="limpar-tudo" type="button" class="btn btn-outline-danger mb-3 me-2">
                                <i class="fas fa-eraser"></i> Limpar
                            </button>
                        </div>
    
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
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" id="finalizar" class="btn btn-outline-warning ">Finalizar compra</button>
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