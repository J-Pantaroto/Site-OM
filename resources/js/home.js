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
                    const produtoDiv = document.createElement('div');
                    produtoDiv.className = 'col-md-4 col-6';
                    const a1 = document.createElement("a");
                    a1.href = `/pesquisar/produto/${encodeURIComponent(produto.nome)}`;
                    a1.className = "text-decoration-none text-black";
                    const div = document.createElement("div");
                    div.className = "card m-4 card-produto";
                    const img = document.createElement("img");
                    img.src = produto.imagem;
                    img.className = "card-img-top";
                    img.setAttribute("alt", produto.nome);
                    div.appendChild(img);
                    const div2 = document.createElement("div");
                    div2.className = "card-body text-center";
                    const h5 = document.createElement("h5");
                    h5.className = "card-title";
                    h5.textContent = produto.nome;
                    const a2 = document.createElement("a");
                    a2.className = "btn btn-warning d-block adicionar-carrinho";
                    a2.textContent = "Adicionar ao carrinho";
                    div2.appendChild(h5);
                    div2.appendChild(a2);
                    a1.appendChild(div);
                    div.appendChild(div2);
                    produtoDiv.appendChild(a1);
                    produtosContainer.appendChild(produtoDiv);
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
    });

    var inputPesquisa = document.getElementById("pesquisa")
    inputPesquisa.addEventListener("keypress", function () {
        if (window.event.keyCode == 13) {
            escopo = "pesquisa"
            const pesquisaInput = document.querySelector('input[name="pesquisa"]').value;
            buscarProdutos(pesquisaInput, escopo, '', 10, 'pesquisar_produto');
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
    // Exemplo de como utilizar dentro do evento de adicionar ao carrinho
    document.querySelector('#produtos-container').addEventListener('click', function (event) {
        if (event.target.classList.contains('adicionar-carrinho')) {
            const produtoSelecionado = event.target.closest('.card-produto');
            const produto = {
                nome: produtoSelecionado.querySelector('.card-title').textContent,
                imagem: produtoSelecionado.querySelector('img').src
            };
            adicionarProdutoCarrinho(produto);
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        atualizarContagemCarrinho();
    });

    function atualizarContagemCarrinho() {
        const produtosCarrinho = carregarProdutosCarrinho();
        const quantidadeTotal = produtosCarrinho.reduce((total, produto) => total + (produto.quantidade || 0), 0);
        document.getElementById("cart-count").innerText = quantidadeTotal;
    }

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
                    const produtoSelecionado = event.target.closest('.card-produto'); // Pega o card do produto e se baseia nele

                    // Imagem
                    const imagemCardAtual = produtoSelecionado.querySelector('img').src; // Pega o caminho da imagem com base no card
                    const produtoCarrinho = document.createElement('tr');

                    const imagemContainer = document.createElement('td');
                    const imagem = document.createElement('img');
                    imagem.src = imagemCardAtual;
                    imagem.style.width = "15vh";
                    imagemContainer.appendChild(imagem);
                    produtoCarrinho.appendChild(imagemContainer);

                    // Nome
                    let nomeProduto = produtoSelecionado.querySelector('.card-title').textContent;
                    const nomeContainer = document.createElement('td');
                    nomeContainer.textContent = nomeProduto;
                    produtoCarrinho.appendChild(nomeContainer);

                    // Quantidade
                    const qntProduto = document.createElement("td");
                    const div = document.createElement("div");
                    div.className = "input-group";
                    // -
                    const botaoMenos = document.createElement("button");
                    botaoMenos.type = "button";
                    botaoMenos.className = "button-minus";
                    botaoMenos.textContent = "-";
                    // quantidade (span)
                    const quantidadeSpan = document.createElement("span");
                    quantidadeSpan.className = "quantity-span";
                    quantidadeSpan.textContent = "1"; // Valor inicial
                    // +
                    const botaoMais = document.createElement("button");
                    botaoMais.type = "button";
                    botaoMais.className = "button-plus";
                    botaoMais.textContent = "+";

                    div.appendChild(botaoMenos);
                    div.appendChild(quantidadeSpan);
                    div.appendChild(botaoMais);
                    qntProduto.appendChild(div);
                    produtoCarrinho.appendChild(qntProduto);

                    // Ação
                    const acao = document.createElement('td');
                    const botaoRemover = document.createElement('button');
                    botaoRemover.className = 'remover-item btn btn-danger';
                    botaoRemover.type = 'button';
                    // Criação do elemento <svg>
                    const svg = document.createElement('svg');
                    svg.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="remover-item bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/></svg>'
                    // Adiciona o SVG ao botão
                    botaoRemover.appendChild(svg);
                    acao.appendChild(botaoRemover);
                    produtoCarrinho.appendChild(acao);
                    // Adiciona o produto ao corpo da tabela
                    const cartItems = document.querySelector('#cartItems');
                    cartItems.appendChild(produtoCarrinho);
                    adicionarProdutoCarrinho(produtoCarrinho);
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

            if (button.classList.contains('button-minus')) {
                let value = parseInt(quantidadeSpan.textContent);
                if (value > 1) {
                    quantidadeSpan.textContent = value - 1;
                    atualizarProdutosCarrinho();
                    atualizarProdutoQuantidade(nomeProduto, value - 1);
                }
            }

            if (button.classList.contains('button-plus')) {
                let value = parseInt(quantidadeSpan.textContent);
                quantidadeSpan.textContent = value + 1;
                atualizarProdutosCarrinho();
                atualizarProdutoQuantidade(nomeProduto, value + 1);
            }
        }
        if (button.classList.contains('remover-item')) {
            const item = button.closest('tr');
            const nomeProduto = item.querySelector('td:nth-child(2)').textContent;
            item.remove();
            let produtosCarrinho = carregarProdutosCarrinho();
            produtosCarrinho = produtosCarrinho.filter(p => p.nome !== nomeProduto);  // Filtra o nome do produto removido e não adiciona ele na variável.
            atualizarProdutosCarrinho();
            atualizarCookiesCarrinho(produtosCarrinho);
            atualizarContagemCarrinho(); // Atualiza o contador após remoção
        }
    });


    function atualizarProdutosCarrinho() {
        const tabela = document.getElementById("tabelaCarrinho");
        const linhas = tabela.getElementsByTagName("tbody")[0].getElementsByTagName("tr");
        let quantidadeTotal = 0;

        for (let i = 0; i < linhas.length; i++) {
            const quantidadeSpan = linhas[i].querySelector(".quantity-span");
            const quantidadeProduto = parseInt(quantidadeSpan.textContent);
            quantidadeTotal += quantidadeProduto;
        }
        document.getElementById("cart-count").innerText = quantidadeTotal;
    }

    function atualizarProdutoQuantidade(nomeProduto, quantidade) {
        const produtosCarrinho = carregarProdutosCarrinho();
        const produto = produtosCarrinho.find(p => p.nome === nomeProduto);
        if (produto) {
            produto.quantidade = quantidade;
        }
        atualizarCookiesCarrinho(produtosCarrinho);
    }

    function atualizarCookiesCarrinho(produtos) {
        fetch('/atualizar/carrinho', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ produtos: produtos })
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'sucesso') {
                    console.log('Carrinho atualizado com sucesso!');
                    atualizarContagemCarrinho();
                }
            })
            .catch(error => console.error('Erro ao atualizar o carrinho:', error));
    }

    function adicionarProdutoCarrinho(produto) {
        const produtosCarrinho = carregarProdutosCarrinho();

        const produtoExistente = produtosCarrinho.find(p => p.nome === produto.nome);
        if (produtoExistente) {
            produtoExistente.quantidade += 1; // Atualiza a quantidade
        } else {
            produto.quantidade = 1; // Adiciona novo produto
            produtosCarrinho.push(produto);
        }

        atualizarCookiesCarrinho(produtosCarrinho); // Atualiza o cookie com os produtos
        atualizarContagemCarrinho();
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
        fetch('/limpar/carrinho', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'sucesso') {
                    console.log('Carrinho limpo com sucesso!');
                }
            })
            .catch(error => console.error('Erro ao limpar o carrinho:', error));
    }






}