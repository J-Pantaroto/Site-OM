<x-main-layout>
<div class="banner">
        <div class="banner-content">
            <h1 class="banner-text">Bem-vindo à nossa loja</h1>
            <p class="banner-text">Confira nossas mercadorias abaixo</p>
            <a href="#produtos-container" class="btn btn-primary mt-3 button-primary">Ver Produtos</a>
        </div>
    </div>
    <div class="container pt-5">
        <div class="dropdown row gx-0">
            <button id="toggleGrupos" class="btn btn-secondary d-none mt-3 w-75 mx-auto button-primary">Mostrar Grupos</button>
            <div class="col-lg-3 col-12 text-center menu_lateral" id="gruposList">
                <ul class="list-group list-group-flush w-75 mx-auto">
                    <li class="list-group-item active list-group-item-action m-0 lista" data-grupo-id="todos">
                        Todos os Produtos
                    </li>
                    @foreach ($grupos as $grupo)
                        <a href="" class="list-group-item list-group-item-action m-0 lista"
                            data-grupo-id="{{ $grupo->id }}">
                            {{$grupo->descricao}}
                        </a>
                    @endforeach
                </ul>
            </div>
            <div class="col-lg-9 col-12">
                <div id="produtos-container" class="row gx-0">
                    @if (empty($produtos))
                        <div class="alert alert-danger" role="alert">
                            Nenhum produto cadastrado
                        </div>
                    @else
                        @foreach ($produtos as $produto)
                            <div class="col-md-4 col-6">
                                <a href="{{ route('produto/', ['nome' => $produto->nome]) }}"
                                    class="text-decoration-none a-text">
                                    <div class="card m-4 card-produto">
                                        <img src="{{ $produto->imagem }}" class="card-img-top img-fluid" alt="...">
                                        <div class="card-body text-center">
                                            <h5 class="card-title produto-nome">{{ $produto->nome }}</h5>
                                            <div class="produto-descricao">
                                                <p>{{ $produto->descricao }}</p>
                                            </div>
                                            <a class="btn btn-primary d-block adicionar-carrinho button-primary" data-id="{{ $produto->id }}">
                                                Adicionar ao carrinho
                                            </a>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="text-center mt-4">
                    <button class="btn btn-primary button-primary" id="verMais">Ver mais produtos</button>
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
                            <button type="button" class="btn btn-primary button-danger" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" id="finalizar" class="btn btn-primary button-primary">Solicitar orcamento</button>
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
    <script src="{{ mix('js/home.js') }}"></script>
</x-main-layout>