<x-main-layout>
    <h3 class="h3 text-center">{{ $prod->nome }}</h3>
    <div class="container mt-5">
        <div class="row gx-0 shadow-lg mt-3 p-4">
            <div class="col-6">
                <img id="imagem" src="{{ asset('storage/' . $prod->imagem) }}" class="card-img-top"
                    alt="{{ $prod->nome }}">
                <div class="thumbnail-container mt-3 d-flex justify-content-center">
                    @foreach ($prod->imagens as $imagem)
                        <img src="{{ asset('storage/' . $imagem->imagem) }}" class="img-thumbnail thumbnail"
                            alt="Miniatura {{ $loop->index }}">
                    @endforeach
                </div>
            </div>
            <div class="col">
                <div class="d-flex flex-column h-100">
                    <div class="card-body">
                        <h3 id="nome-produto" class="card-title text-center">{{ $prod->nome }}</h3>
                        <h5 class="card-text text-center p-4">{{ $prod->descricao }}</h5>
                        <p class="card-text text-start">CÓDIGO:{{ $prod->codigo}}</p>
                        @if (config('config.config.validar_estoque') === 'S')
                        <p class="produto-quantidade"> Quantidade disponível: {{$prod->quantidade}}</p>
                        @endif
                        @if (!empty($prod->preco) && config('config.config.exibir_preco') === 'S')
                            <h5 class="produto-preco text-end">Preço: R$ {{ $prod->preco }}</h5>
                        @endif
                    </div>
                    <div class="mt-auto d-flex justify-content-end">
                        <button class="btn btn-primary button-primary" id="adicionar-carrinho"
                            data-id="{{ $prod->id }}" type="submit">
                            <i class="fas fa-shopping-cart"></i> Adicionar ao carrinho
                        </button>
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
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <h2 class="text-center">Carrinho de Orcamentos</h2>
                            <div class="mt-auto d-flex justify-content-end">
                                <button id="limpar-tudo" type="button" class="btn btn-primary mb-3 me-2 button-danger">
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
                                                    @if (config('config.config.exibir_preco') === 'S')
                                                        <th>Preço</th>
                                                        <th>Subtotal</th>
                                                    @endif
                                                    <th>Ação</th>
                                                </tr>
                                            </thead>
                                            <tbody id="cartItems">
                                            </tbody>
                                        </table>
                                        @if (config('config.config.exibir_preco') === 'S')
                                            <h5>Total: <span id="cartTotal">R$ 0,00</span></h5>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary button-danger"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="button" id="finalizar" class="btn btn-primary button-primary">Solicitar
                                    orcamento</button>
                            </div>
                        </div>
                    </div>
                </div>
                <a type="button" id="comprar-btn" data-bs-toggle="modal" data-bs-target="#modal-loja"
                    class="floating-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <span id="cart-count">0</span>
                </a>
            @endauth
        @endif
        <script src="{{ mix('js/produto.js') }}"></script>
</x-main-layout>
