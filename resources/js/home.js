import Swal from 'sweetalert2';
var escopo = 'todos';
var offset2 = 0
var usuarioAutenticado = document.getElementById('usuario-autenticado').dataset.autenticado === 'true';
async function buscarProdutos(pesquisa = '', escopo, categoria = '', limite, tipo_chamada) {
    try {
        if (tipo_chamada == "mais_produto") {
            offset2 += parseInt(limite);
        } else {
            offset2 = 0;
        }
        const resposta = await fetch(`/buscar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                pesquisa: pesquisa,
                categoria: categoria,
                limite: limite,
                offset: offset2,
                escopo: escopo,
            })
        });

        const textoResposta = await resposta.json();
        const produtosContainer = document.getElementById('produtos-container');
        const botaoVerMais = document.getElementById("verMais");

        if (textoResposta.status === 'sucesso') {
            const quantidade = textoResposta.quantidade;
            const produtos = textoResposta.produtos;

            if (tipo_chamada != 'mais_produto') {
                produtosContainer.innerHTML = ''; // Limpa os produtos existentes ao realizar uma nova busca
            }

            if (quantidade === 0) {
                produtosContainer.innerHTML = '<p>Produto não encontrado!</p>';
                botaoVerMais.style.display = "none"; // Oculta o botão se não houver produtos
            } else {
                produtos.forEach(produto => {
                    // Cria o container do produto
                    const produtoDiv = document.createElement('div');
                    produtoDiv.className = 'col-md-4 col-6';

                    // Cria o card do produto
                    const divCard = document.createElement("div");
                    divCard.className = "card m-4 card-produto";

                    // Cria o link para o produto (só para imagem e título)
                    const linkProduto = document.createElement("a");
                    linkProduto.href = `/pesquisar/produto/${encodeURIComponent(produto.nome)}`;
                    linkProduto.className = "text-decoration-none text-black";

                    // Cria a imagem do produto
                    const img = document.createElement("img");
                    img.src = produto.imagem;
                    img.className = "card-img-top img-fluid";
                    img.setAttribute("alt", produto.nome);

                    // Adiciona a imagem ao link
                    linkProduto.appendChild(img);

                    // Adiciona o link (com a imagem) ao card
                    divCard.appendChild(linkProduto);

                    // Cria a div do card-body
                    const divCardBody = document.createElement("div");
                    divCardBody.className = "card-body text-center";

                    // Cria o link para o nome do produto
                    const linkNome = document.createElement("a");
                    linkNome.href = `/pesquisar/produto/${encodeURIComponent(produto.nome)}`;
                    linkNome.className = "text-decoration-none text-black";

                    // Cria o título com o nome do produto
                    const h5 = document.createElement("h5");
                    h5.className = "card-title produto-nome";
                    h5.textContent = produto.nome;
                    if (produto.nome.length > 22) {
                        h5.classList.add("fs-6");
                    }

                    // Adiciona o título ao link
                    linkNome.appendChild(h5);

                    // Adiciona o link do nome ao card-body
                    divCardBody.appendChild(linkNome);

                    // Cria a descrição do produto
                    const divDescricao = document.createElement("div");
                    divDescricao.className = "produto-descricao";
                    const paragrafo = document.createElement("p");
                    paragrafo.textContent = produto.descricao;

                    // Adiciona a descrição ao card-body
                    divDescricao.appendChild(paragrafo);
                    divCardBody.appendChild(divDescricao);

                    // Cria o botão de "Adicionar ao carrinho"
                    const botaoAdicionar = document.createElement("a");
                    botaoAdicionar.className = "btn btn-warning d-block adicionar-carrinho";
                    botaoAdicionar.textContent = "Adicionar ao carrinho";
                    botaoAdicionar.setAttribute('data-id', produto.id);  // Adiciona o ID do produto

                    // Adiciona o botão ao card-body (fora do link do produto)
                    divCardBody.appendChild(botaoAdicionar);

                    // Adiciona o card-body ao card
                    divCard.appendChild(divCardBody);

                    // Adiciona o card ao container do produto
                    produtoDiv.appendChild(divCard);

                    // Adiciona o container do produto ao container principal (produtosContainer)
                    produtosContainer.appendChild(produtoDiv);
                    window.dispatchEvent(new Event('resize'));
                });


                if (textoResposta.totalProdutos > textoResposta.quantidade) {
                    botaoVerMais.removeAttribute("style"); // Exibe o botão se houver mais de 10 produtos
                } else {
                    botaoVerMais.style.display = "none"; // Oculta o botão se houver 10 ou menos produtos
                }
            }
        } else {
            console.error('Erro:', textoResposta.mensagem);
        }
    } catch (error) {
        console.error('Erro:', error);
    }
}

//PESQUISA
document.addEventListener('DOMContentLoaded', function () {
    const searchBtn = document.getElementById('search-btn');
    searchBtn.addEventListener('click', (event) => {
        event.preventDefault();
        escopo = "pesquisa"
        const pesquisaInput = document.querySelector('input[name="pesquisa"]').value;
        buscarProdutos(pesquisaInput, escopo, '', 10, 'pesquisar_produto');
        const listItems = document.querySelectorAll('.lista');
        listItems.forEach(li => li.classList.remove('active'));
        const todosProdutos = document.querySelector('[data-grupo-id="todos"]');
        todosProdutos.classList.add('active');
        window.dispatchEvent(new Event('resize'));
    });

    var inputPesquisa = document.getElementById("pesquisa")
    inputPesquisa.addEventListener("keypress", function () {
        if (window.event.keyCode == 13) {
            escopo = "pesquisa"
            const pesquisaInput = document.querySelector('input[name="pesquisa"]').value;
            buscarProdutos(pesquisaInput, escopo, '', 10, 'pesquisar_produto');
            window.dispatchEvent(new Event('resize'));
        }
    })
})


//CATEGORIA
const listItems = document.querySelectorAll('.lista');

listItems.forEach(item => {
    item.addEventListener('click', (event) => {
        escopo = item.getAttribute('data-grupo-id')
        event.preventDefault();
        // Remover classe ativa de todos os itens
        listItems.forEach(li => li.classList.remove('active'));
        // Adicionar classe ativa ao item clicado
        document.querySelector('input[name="pesquisa"]').value = '';
        item.classList.add('active');
        buscarProdutos('', escopo, escopo, 10, 'categoria_produto');
    });
});
//VER MAIS
document.getElementById("verMais").addEventListener("click", function () {
    buscarProdutos('', escopo, escopo, 10, 'mais_produto')
    window.dispatchEvent(new Event('resize'));
})

//dropdown
document.addEventListener('DOMContentLoaded', function () {
    window.abrirFecharDropDown = function (escopo) {
        var dropdown = document.getElementById('drop');
        if (escopo == "enter") {
            dropdown.setAttribute('data-bs-popper', 'static');
            dropdown.classList.add('show');
        } else if (escopo == "leave") {
            dropdown.removeAttribute('data-bs-popper');
            dropdown.classList.remove('show');
        }
    }
});

if (usuarioAutenticado) {
    document.querySelector('#produtos-container').addEventListener('click', function (event) {
        if (event.target.classList.contains('adicionar-carrinho')) {
            const produtoSelecionado = event.target.closest('.card-produto');
            const produto = {
                nome: produtoSelecionado.querySelector('.card-title').textContent,
                imagem: produtoSelecionado.querySelector('img').src
            };
            adicionarProdutoCarrinho(produto);
            atualizarCarrinho();
            atualizarContagemCarrinho();
        }
    });

    function atualizarCarrinho() {
        const produtosCarrinho = carregarProdutosCarrinho(); // Carrega os produtos do cookie
        const cartItems = document.querySelector('#cartItems');

        cartItems.innerHTML = ''; // Limpa os itens do carrinho

        produtosCarrinho.forEach(produto => {
            const produtoCarrinho = document.createElement('tr');

            const imagemContainer = document.createElement('td');
            const imagem = document.createElement('img');
            imagem.src = produto.imagem; // Usa o caminho da imagem salvo no cookie
            imagem.style.width = "15vh";
            imagemContainer.appendChild(imagem);
            produtoCarrinho.appendChild(imagemContainer);

            const nomeContainer = document.createElement('td');
            nomeContainer.textContent = produto.nome; // Usa o nome do produto salvo no cookie
            produtoCarrinho.appendChild(nomeContainer);

            const qntProduto = document.createElement("td");
            const div = document.createElement("div");
            div.className = "input-group";

            const botaoMenos = document.createElement("button");
            botaoMenos.type = "button";
            botaoMenos.className = "button-minus";
            botaoMenos.textContent = "-";

            const quantidadeSpan = document.createElement("span");
            quantidadeSpan.className = "quantity-span";
            quantidadeSpan.textContent = produto.quantidade; // Usa a quantidade salva no cookie

            const botaoMais = document.createElement("button");
            botaoMais.type = "button";
            botaoMais.className = "button-plus";
            botaoMais.textContent = "+";

            div.appendChild(botaoMenos);
            div.appendChild(quantidadeSpan);
            div.appendChild(botaoMais);
            qntProduto.appendChild(div);
            produtoCarrinho.appendChild(qntProduto);

            const acao = document.createElement('td');
            const botaoRemover = document.createElement('button');
            botaoRemover.className = 'remover-item btn btn-danger';
            botaoRemover.type = 'button';
            const svg = document.createElement('svg');
            svg.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="remover-item bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/></svg>';
            botaoRemover.appendChild(svg);
            acao.appendChild(botaoRemover);
            produtoCarrinho.appendChild(acao);
            cartItems.appendChild(produtoCarrinho);
        });
    }
    document.addEventListener('DOMContentLoaded', function () {
        atualizarCarrinho();
        atualizarContagemCarrinho();
    });
    //CARRINHO FUNCTION
    document.addEventListener('DOMContentLoaded', () => {
        const addCarrinho = document.querySelector('#produtos-container');
        if (addCarrinho) {
            addCarrinho.addEventListener('click', function (event) {
                if (event.target.classList.contains('adicionar-carrinho')) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    });
                    Toast.fire({
                        icon: "success",
                        title: "Adicionado ao carrinho"
                    });

                    const produtoSelecionado = event.target.closest('.card-produto');
                    let nomeProduto = produtoSelecionado.querySelector('.card-title').textContent;

                    const cartItems = document.querySelector('#cartItems');
                    let produtoExistente = Array.from(cartItems.querySelectorAll('tr')).find(row => {
                        return row.querySelector('td:nth-child(2)').textContent === nomeProduto;
                    });

                    if (produtoExistente) {
                        const quantidadeSpan = produtoExistente.querySelector('.quantity-span');
                        let quantidadeAtual = parseInt(quantidadeSpan.textContent);
                        quantidadeSpan.textContent = quantidadeAtual + 1;
                    } else {
                        const imagemCardAtual = produtoSelecionado.querySelector('img').src;
                        const produtoCarrinho = document.createElement('tr');

                        const produto = {
                            nome: nomeProduto,
                            imagem: imagemCardAtual,
                            quantidade: 1
                        };

                        const imagemContainer = document.createElement('td');
                        const imagem = document.createElement('img');
                        imagem.src = imagemCardAtual;
                        imagem.style.width = "15vh";
                        imagemContainer.appendChild(imagem);
                        produtoCarrinho.appendChild(imagemContainer);

                        const nomeContainer = document.createElement('td');
                        nomeContainer.textContent = nomeProduto;
                        produtoCarrinho.appendChild(nomeContainer);

                        const qntProduto = document.createElement("td");
                        const div = document.createElement("div");
                        div.className = "input-group";

                        const botaoMenos = document.createElement("button");
                        botaoMenos.type = "button";
                        botaoMenos.className = "button-minus";
                        botaoMenos.textContent = "-";

                        const quantidadeSpan = document.createElement("span");
                        quantidadeSpan.className = "quantity-span";
                        quantidadeSpan.textContent = "1";

                        const botaoMais = document.createElement("button");
                        botaoMais.type = "button";
                        botaoMais.className = "button-plus";
                        botaoMais.textContent = "+";

                        div.appendChild(botaoMenos);
                        div.appendChild(quantidadeSpan);
                        div.appendChild(botaoMais);
                        qntProduto.appendChild(div);
                        produtoCarrinho.appendChild(qntProduto);

                        const acao = document.createElement('td');
                        const botaoRemover = document.createElement('button');
                        botaoRemover.className = 'remover-item btn btn-danger';
                        botaoRemover.type = 'button';
                        const svg = document.createElement('svg');
                        svg.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="remover-item bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/></svg>';
                        botaoRemover.appendChild(svg);
                        acao.appendChild(botaoRemover);
                        produtoCarrinho.appendChild(acao);

                        cartItems.appendChild(produtoCarrinho);
                    }

                    const produtoParaAdicionar = {
                        nome: nomeProduto,
                        imagem: produtoSelecionado.querySelector('img').src
                    };

                    adicionarProdutoCarrinho(produtoParaAdicionar);
                }
            });
        }
    });

    const cartItems = document.getElementById('cartItems');
    cartItems.addEventListener('click', function (event) {
        const button = event.target;
        const inputGrupo = button.closest('.input-group');
        if (inputGrupo) {
            const quantidadeSpan = inputGrupo.querySelector('.quantity-span');
            const item = button.closest('tr');
            const nomeProduto = item.querySelector('td:nth-child(2)').textContent;
            let value = parseInt(quantidadeSpan.textContent);

            if (button.classList.contains('button-minus')) {
                if (value > 1) {
                    value -= 1;
                    quantidadeSpan.textContent = value;
                    atualizarProdutoQuantidade(nomeProduto, value).then(() => {
                        atualizarContagemCarrinho();
                    });
                } else if (value === 1) {
                    item.remove();
                    let produtosCarrinho = carregarProdutosCarrinho();
                    produtosCarrinho = produtosCarrinho.filter(p => p.nome !== nomeProduto);
                    atualizarCookiesCarrinho(produtosCarrinho).then(() => {
                        atualizarContagemCarrinho();
                    });
                }
            }

            if (button.classList.contains('button-plus')) {
                value += 1;
                quantidadeSpan.textContent = value;
                atualizarProdutoQuantidade(nomeProduto, value).then(() => {
                    atualizarContagemCarrinho();
                });
            }
        }

        if (button.classList.contains('remover-item')) {
            const item = button.closest('tr');
            const nomeProduto = item.querySelector('td:nth-child(2)').textContent;
            item.remove();
            let produtosCarrinho = carregarProdutosCarrinho();
            produtosCarrinho = produtosCarrinho.filter(p => p.nome !== nomeProduto);
            atualizarCookiesCarrinho(produtosCarrinho).then(() => {
                atualizarContagemCarrinho();
            });
        }
    });

    function atualizarProdutoQuantidade(nomeProduto, quantidade) {
        const produtosCarrinho = carregarProdutosCarrinho();
        const produto = produtosCarrinho.find(p => p.nome === nomeProduto);
        if (produto) {
            produto.quantidade = quantidade;
        }
        return atualizarCookiesCarrinho(produtosCarrinho);
    }

    async function atualizarCookiesCarrinho(produtos) {
        try {
            const response = await fetch('/atualizar/carrinho', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ produtos: produtos })
            });
            const data = await response.json();
            if (data.status === 'sucesso') {
                console.log('Carrinho atualizado com sucesso!');
            }
        } catch (error) {
            console.error('Erro ao atualizar o carrinho:', error);
        }
    }

    function atualizarContagemCarrinho() {
        const produtosCarrinho = carregarProdutosCarrinho();
        let quantidadeTotal = 0;

        if (produtosCarrinho.length === 0) {
            const tabela = document.getElementById("tabelaCarrinho");
            const linhas = tabela.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

            for (let i = 0; i < linhas.length; i++) {
                const quantidadeSpan = linhas[i].querySelector(".quantity-span");
                const quantidadeProduto = parseInt(quantidadeSpan.textContent);
                quantidadeTotal += quantidadeProduto;
            }
        } else {
            quantidadeTotal = produtosCarrinho.reduce((total, produto) => total + produto.quantidade, 0);
        }

        document.getElementById("cart-count").textContent = quantidadeTotal;
    }

    function adicionarProdutoCarrinho(produto) {
        const produtosCarrinho = carregarProdutosCarrinho();
        const produtoExistente = produtosCarrinho.find(p => p.nome === produto.nome);

        if (produtoExistente) {
            produtoExistente.quantidade += 1;
        } else {
            produto.quantidade = 1;
            produtosCarrinho.push(produto);
        }

        atualizarCookiesCarrinho(produtosCarrinho).then(() => {
            atualizarContagemCarrinho();
        });
    }

    function carregarProdutosCarrinho() {
        const produtos = JSON.parse(getCookie('carrinho')) || [];
        return Array.isArray(produtos) ? produtos : [];
    }

    function getCookie(nome) {
        const value = `; ${decodeURIComponent(document.cookie)}`;
        const parts = value.split(`; ${nome}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }

    document.addEventListener('DOMContentLoaded', () => {
        const finalizarCompra = document.getElementById('finalizar');
        finalizarCompra.addEventListener('click', function (event) {
            Swal.fire({
                icon: "success",
                title: "Compra finalizada!",
                text: "Seu pedido já foi enviado, logo um dos nossos colaboradores entrará em contato com você!",
                showClass: {
                    popup: `
                        animate__rubberBand
                        animate__backOutUp
                      `
                },
                hideClass: {
                    popup: `
                        animate__backOutDown
                      `
                }
            });
            // Carregar os produtos do carrinho
            const produtosCarrinho = carregarProdutosCarrinho();
            console.log("PRODUTOSCARRINHO:");
            console.log(produtosCarrinho);
            const produtosFormatados = produtosCarrinho.map(produto => ({
                id: produto.id,
                quantidade: produto.quantidade
            }));

            // Enviar a requisição POST para o servidor
            fetch(`/registrar/venda`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    produtos: produtosFormatados
                })
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Venda registrada com sucesso:', data);
                })
                .catch(error => {
                    console.error('Erro ao registrar venda:', error);
                });
            //fechar e limpar carrinho
            limparCarrinho();
            const carrinhoModal = document.getElementById('modal-loja');
            carrinhoModal.classList.remove('show');
            carrinhoModal.style.display = "none";

            const modalBackdrop = document.querySelector('.modal-backdrop');
            if (modalBackdrop) {
                modalBackdrop.remove();
            }

            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('padding-right');
        });
    });

    function limparCarrinho() {
        document.cookie = "carrinho=" + encodeURIComponent(JSON.stringify([])) + "; path=/;";

        const cartItems = document.querySelector('#cartItems');
        cartItems.innerHTML = '';

        atualizarContagemCarrinho();
    }

    const limpaBotao = document.getElementById('limpar-tudo');
    limpaBotao.addEventListener('click', function (event) {
        limparCarrinho();
    });

    window.addEventListener('pageshow', function (event) {
        atualizarCarrinho();
        atualizarContagemCarrinho();
    });


} else if (!usuarioAutenticado) {
    const containerProdutos = document.querySelector('#produtos-container');
    if (containerProdutos) {
        containerProdutos.addEventListener('click', function (event) {
            if (event.target.classList.contains('adicionar-carrinho')) {
                let timerInterval;
                Swal.fire({
                    title: "Você ainda não está logado!",
                    html: "Você será redirecionado para a página de login em <b></b> segundos.",
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                        const timer = Swal.getPopup().querySelector("b");
                        let countdown = 3;
                        timerInterval = setInterval(() => {
                            countdown--;
                            timer.textContent = countdown;
                        }, 1000);
                    },
                    willClose: () => {
                        clearInterval(timerInterval);
                        window.location = "/login";
                    }
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        console.log("Redirecionado pelo timer");
                    }
                });
            }
        });
    }
}
const toggleButton = document.getElementById('toggleGrupos');
const gruposList = document.getElementById('gruposList');
function checkScreenSize() {
    if (window.innerWidth < 990) {
        gruposList.classList.add('d-none');
        toggleButton.classList.remove('d-none');
        toggleButton.textContent = 'Mostrar Grupos';
    } else {
        gruposList.classList.remove('d-none');
        toggleButton.classList.add('d-none');
    }
}

toggleButton.addEventListener('click', () => {
    gruposList.classList.toggle('d-none');
    toggleButton.textContent = gruposList.classList.contains('d-none') ? 'Mostrar Grupos' : 'Ocultar Grupos';
});
function adjustLayout() {
    const cards = document.querySelectorAll('.card-produto');
    const botoesAdd = document.querySelectorAll('.adicionar-carrinho');
    const linksAdd = document.querySelectorAll('.text-decoration-none.text-black');
    const titulosAdd = document.querySelectorAll('.card-title.produto-nome');

    cards.forEach(card => {
        card.classList.remove('m-4', 'm-3', 'm-1');
        if (window.innerWidth < 1000) {
            card.classList.add('m-1');
        } else if (window.innerWidth < 1400) {
            card.classList.add('m-3');
        } else {
            card.classList.add('m-4');
        }
    });

    botoesAdd.forEach(botaoAdd => {
        botaoAdd.classList.remove('btn-sm');
        if (window.innerWidth < 1000) {
            botaoAdd.classList.add('btn-sm');
        }
    });

    titulosAdd.forEach(tituloAdd => {
        tituloAdd.classList.remove('fs-6');
        if (window.innerWidth < 1000) {
            tituloAdd.classList.add('fs-6');
        }
    });

    linksAdd.forEach(linkAdd => {
        linkAdd.classList.remove('fs-6');
        if (window.innerWidth < 1000) {
            linkAdd.classList.add('fs-6');
        }
    });
}
window.addEventListener('resize', () => {
    checkScreenSize();
    adjustLayout();
});

window.addEventListener('load', () => {
    checkScreenSize();
    adjustLayout();
});

window.dispatchEvent(new Event('resize'));