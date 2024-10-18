import Swal from 'sweetalert2';
var usuarioAutenticado = document.getElementById('usuario-autenticado').dataset.autenticado === 'true';

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

    document.getElementById('adicionar-carrinho').addEventListener('click', function (event) {
        const produto = {
            nome: document.getElementById('nome-produto').textContent,
            imagem: document.getElementById('imagem').src
        };
        adicionarProdutoCarrinho(produto);
        atualizarCarrinho();
        atualizarContagemCarrinho();
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
        const addCarrinho = document.getElementById('adicionar-carrinho');
        if (addCarrinho) {
            addCarrinho.addEventListener('click', function (event) {
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

                let nomeProduto = document.getElementById('nome-produto').textContent;

                const cartItems = document.querySelector('#cartItems');
                let produtoExistente = Array.from(cartItems.querySelectorAll('tr')).find(row => {
                    return row.querySelector('td:nth-child(2)').textContent === nomeProduto;
                });

                if (produtoExistente) {
                    const quantidadeSpan = produtoExistente.querySelector('.quantity-span');
                    let quantidadeAtual = parseInt(quantidadeSpan.textContent);
                    quantidadeSpan.textContent = quantidadeAtual + 1;
                } else {
                    const imagemCardAtual = document.getElementById('imagem').src;
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

                const produto = {
                    nome: document.getElementById('nome-produto').textContent,
                    imagem: document.getElementById('imagem').src,
                    quantidade: 1
                };
                adicionarProdutoCarrinho(produto);
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



    function limparCarrinho() {
        // Limpa o conteúdo do cookie
        document.cookie = "carrinho=" + encodeURIComponent(JSON.stringify([])) + "; path=/;";

        const cartItems = document.querySelector('#cartItems');
        cartItems.innerHTML = '';

        atualizarContagemCarrinho();
    }

    //LIMPAR TODO O CARRINHO BOTAO
    const limpaBotao = document.getElementById('limpar-tudo');
    limpaBotao.addEventListener('click', function (event) {
        limparCarrinho();
    });


    //ATUALIZAR CARRINHO AO CLICAR EM VOLTAR OU AVANÇAR (SETINHA DO NAVEGADOR)
    window.addEventListener('pageshow', function (event) {
        atualizarCarrinho();
        atualizarContagemCarrinho();
    });



} else if (!usuarioAutenticado) {
    const addCarrinho = document.getElementById('adicionar-carrinho');
    addCarrinho.addEventListener('click', function (event) {
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
    });
}
