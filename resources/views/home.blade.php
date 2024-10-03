<x-main-layout>
    <div id="carouselExampleIndicators" class="carousel carousel-dark slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('images/carrosel1.jpg') }}" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/carrosel2.jpg') }}" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/carrosel3.jpg') }}" class="d-block w-100" alt="...">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <div class="container pt-5">
        <div class="dropdown row gx-0">
            <div class="col-3 text-center menu_lateral">
                <ul class="list-group list-group-flush w-75 m-4">
                    <a href="" class="list-group-item active list-group-item-action m-0 lista"
                        data-grupo-id="todos">Todos os Produtos</a>
                    @foreach ($grupos as $grupo)
                        <a href="" class="list-group-item list-group-item-action m-0 lista"
                            data-grupo-id="{{ $grupo->id }}">{{$grupo->descricao}}</a>
                    @endforeach
                </ul>
            </div>
            <div class="col-9">
                <div id="produtos-container" class="row gx-0">
                    @if (empty($produtos))
                        <div class="alert alert-danger" role="alert">
                            Nenhum produto cadastrado
                        </div>
                    @else
                            @foreach ($produtos as $produto)
                                    <div class="col-md-4 col-6">
                                        <a href="{{ route('produto/', ['nome' => $produto->nome]) }}"
                                            class="text-decoration-none text-black">
                                            <div class="card m-4 card-produto">
                                                <img src="{{ $produto->imagem }}" class="card-img-top" alt="...">
                                                <div class="card-body text-center">
                                                    <h5 class="card-title">{{ $produto->nome }}</h5>
                                                    <a class="btn btn-warning d-block adicionar-carrinho"
                                                        data-id="{{ $produto->id }}">Adicionar
                                                        ao carrinho</a>
                                                </div>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                    @endif
            </div>
            <div class=" text-center mt-4">
                <button class="btn btn-warning" id="verMais">Ver mais produtos</button>
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
                @endauth
            @endif
    </a>
    <script src="{{ mix('js/home.js') }}"></script>
</x-main-layout>