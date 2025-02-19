import Swal from 'sweetalert2';
var escopo = 'todos';
var offset2 = 0
var usuarioAutenticado = document.getElementById('usuario-autenticado').dataset.autenticado === 'true';
var validarQuantidade = document.getElementById('validar-estoque').dataset.estoque === 'true';
var logadoCarrinho = document.getElementById('logado-carrinho').dataset.carrinho === 'true';
var exibirPreco = document.body.dataset.exibirPreco === 'true';
async function buscarProdutos({ pesquisa = '', grupo = '', subgrupo = '', limite = 12, tipo_chamada = 'nova_busca', escopo = 'todos' }) {
    try {
        if (tipo_chamada === "mais_produto") {
            offset2 += parseInt(limite);
        } else {
            offset2 = 0;
        }

        const resposta = await fetch(`/buscar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                pesquisa,
                grupo,
                subgrupo,
                limite,
                offset: offset2,
                escopo,
            }),
        });

        const data = await resposta.json();
        const produtosContainer = document.getElementById('produtos-container');
        const botaoVerMais = document.getElementById("verMais");
        const subgruposList = document.getElementById(`subgrupos-${grupo}`);

        if (data.status === 'sucesso') {
            const produtos = data.produtos;
            const quantidade = data.quantidade;

            if (data.subgrupos && grupo && subgruposList) { // Verifica se subgruposList não é null
                if (!subgruposList.hasChildNodes()) { // Só adiciona subgrupos se a lista estiver vazia
                    subgruposList.innerHTML = data.subgrupos
                        .map(subgrupo => `
                            <a href="#" class="list-group-item list-group-item-action subgrupo-item" data-subgrupo-id="${subgrupo.codigo}">
                                ${subgrupo.descricao}
                            </a>
                        `)
                        .join('');
                }
            }

            if (tipo_chamada !== 'mais_produto') {
                produtosContainer.innerHTML = '';
            }

            if (quantidade === 0) {
                produtosContainer.innerHTML = '<p>Produto não encontrado!</p>';
                botaoVerMais.style.display = "none";
            } else {
                produtos.forEach(produto => {
                    const produtoDiv = document.createElement('div');
                    const validarBotao = produto.quantidade > 0;
                    produtoDiv.className = 'col-md-4 col-6';
                    produtoDiv.innerHTML = `
                        <div class="card m-4 card-produto">
                            ${!validarQuantidade && produto.quantidade ? `<p class= "produto-quantidade d-none">${produto.quantidade}</p>` : ''}
                            <a href="/pesquisar/produto/${encodeURIComponent(produto.slug)}" class="text-decoration-none a-text">
                                <img src="${produto.imagem}" class="card-img-top img-fluid" alt="${produto.nome}">
                            </a>
                            <div class="card-body text-center">
                                <a href="/pesquisar/produto/${encodeURIComponent(produto.slug)}" class="text-decoration-none a-text">
                                    <h5 class="card-title produto-nome">${produto.nome}</h5>
                                    ${exibirPreco && produto.preco ? `<p class="produto-preco">R$ ${produto.preco}</p>` : ''}
                                    ${!validarQuantidade ? `<p class="produto-quantidade">Quantidade em estoque: ${produto.quantidade}</p>` : ''}
                                </a>
                                <p class="produto-descricao">${produto.descricao}</p>
                                ${validarBotao ? `<a class="btn btn-primary d-block adicionar-carrinho button-primary" data-id="${produto.id}">Adicionar ao carrinho</a>` : `<a class="btn btn-dark d-block avise-me button-dark" data-id="${produto.id}">Avise-me quando chegar</a>`}
                            </div>
                        </div>`;
                    produtosContainer.appendChild(produtoDiv);
                });

                if (data.totalProdutos > offset2 + quantidade) {
                    botaoVerMais.classList.remove('d-none');
                } else {
                    botaoVerMais.classList.add('d-none');
                }
            }
        } else {
            console.error('Erro:', data.mensagem);
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
        buscarProdutos({ pesquisa: pesquisaInput, grupo: '', subgrupo: '', limite: 12, tipo_chamada: 'pesquisar_produto', escopo: escopo });
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
            buscarProdutos({ pesquisa: pesquisaInput, grupo: '', subgrupo: '', limite: 12, tipo_chamada: 'pesquisar_produto', escopo: escopo });
            window.dispatchEvent(new Event('resize'));
        }
    })
})

//VER MAIS
document.getElementById("verMais").addEventListener("click", function () {
    const pesquisaInput = document.querySelector('input[name="pesquisa"]').value; // Captura o valor atual da pesquisa

    if (pesquisaInput === '' || pesquisaInput === null) {
        const activeItem = document.querySelector('.list-group-item.active'); // Use o seletor correto baseado na estrutura do HTML
        const categoriaSelecionada = activeItem ? activeItem.getAttribute('data-grupo-id') : ''; // Se não houver item ativo, assume vazio
        const subCategoriaSelecionada = activeItem ? activeItem.getAttribute('data-subgrupo-id') : ''; // Se não houver item ativo, assume vazio
        buscarProdutos({ pesquisa: pesquisaInput, grupo: categoriaSelecionada, subgrupo: subCategoriaSelecionada, limite: 12, tipo_chamada: 'mais_produto', escopo: escopo });
        window.dispatchEvent(new Event('resize'));
    } else {
        const subCategoriaSelecionada = null;
        const categoriaSelecionada = null;
        buscarProdutos({ pesquisa: pesquisaInput, grupo: categoriaSelecionada, subgrupo: subCategoriaSelecionada, limite: 12, tipo_chamada: 'mais_produto', escopo: escopo });
        window.dispatchEvent(new Event('resize'));
    }
});


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

document.querySelector('#produtos-container').addEventListener('click', function (event) {
    if (event.target.classList.contains('adicionar-carrinho')) {
        if (logadoCarrinho && !usuarioAutenticado) {
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
            });
            return; // Impede a execução do resto da função
        }

        const buttonPrevent = event.target;
        if (buttonPrevent.disabled) return;

        const produtoSelecionado = event.target.closest('.card-produto');
        const nomeProduto = produtoSelecionado.querySelector('.card-title').textContent;
        const imagemProduto = produtoSelecionado.querySelector('img').src;
        const quantidadeDisponivel = parseInt(produtoSelecionado.querySelector('.produto-quantidade')?.textContent.replace(/\D/g, '')) || 0;
        const quantidadeSolicitada = 1;

        const cartItems = document.querySelector('#cartItems');
        let produtoExistente = Array.from(cartItems.querySelectorAll('tr')).find(row => {
            return row.querySelector('td:nth-child(2)').textContent === nomeProduto;
        });

        if (produtoExistente) {
            const quantidadeAtual = parseInt(produtoExistente.querySelector('.quantity-span').textContent);
            if (quantidadeAtual + quantidadeSolicitada > quantidadeDisponivel) {
                Swal.fire({
                    icon: 'error',
                    title: 'Produto fora de estoque!',
                    text: 'Este produto não está disponível no momento.',
                });
                return;
            }
        }

        if (quantidadeSolicitada > quantidadeDisponivel) {
            Swal.fire({
                icon: 'error',
                title: 'Produto fora de estoque!',
                text: 'Este produto não está disponível no momento.',
            });
            return;
        }

        let produtoParaAdicionar;
        if (exibirPreco) {
            const precoProduto = parseFloat(
                produtoSelecionado.querySelector('.produto-preco').textContent.replace('R$', '').trim()
            );
            produtoParaAdicionar = {
                nome: nomeProduto,
                imagem: imagemProduto,
                preco: precoProduto,
                quantidade: 1
            };
        } else {
            produtoParaAdicionar = {
                nome: nomeProduto,
                imagem: imagemProduto,
                preco: null,
                quantidade: 1
            };
        }

        adicionarProdutoCarrinho(produtoParaAdicionar);
        verificarBotaoFinalizar();

        setTimeout(() => {
            buttonPrevent.disabled = false;
        }, 500);
    }
});


function atualizarCarrinho() {
    if (logadoCarrinho && !usuarioAutenticado) return;
    const produtosCarrinho = carregarProdutosCarrinho(); // Carrega os produtos do cookie
    const cartItems = document.querySelector('#cartItems');

    cartItems.innerHTML = ''; // Limpa os itens do carrinho
    let total = 0;
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
        if (exibirPreco) {

            const precoCell = document.createElement('td');
            precoCell.textContent = `R$ ${produto.preco.toFixed(2)}`;
            produtoCarrinho.appendChild(precoCell)
            const subtotalCell = document.createElement('td');
            subtotalCell.className = "subtotal-cell";
            const subtotal = produto.preco * produto.quantidade;
            subtotalCell.textContent = `R$ ${subtotal.toFixed(2)}`;
            produtoCarrinho.appendChild(subtotalCell);

            total += subtotal;
        }

        const acao = document.createElement('td');
        const botaoRemover = document.createElement('button');
        botaoRemover.className = 'remover-item btn btn-danger';
        botaoRemover.type = 'button';
        botaoRemover.addEventListener('click', () => atualizarProdutoQuantidade(produto.nome, 0));
        const svg = document.createElement('svg');
        svg.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="remover-item bi bi-trash" viewBox="0 0 16 16"><path class="remover-item"  d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/><path class="remover-item" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/></svg>';
        botaoRemover.appendChild(svg);
        acao.appendChild(botaoRemover);
        produtoCarrinho.appendChild(acao);
        cartItems.appendChild(produtoCarrinho);

    });
    if (exibirPreco) {
        document.querySelector('#cartTotal').textContent = `R$ ${total.toFixed(2)}`;
    }
    verificarBotaoFinalizar();

}
document.addEventListener('DOMContentLoaded', function () {
    if (logadoCarrinho && !usuarioAutenticado) return;
    atualizarCarrinho();
    atualizarContagemCarrinho();
    verificarBotaoFinalizar();

});
//CARRINHO FUNCTION
document.addEventListener('DOMContentLoaded', () => {
    const addCarrinho = document.querySelector('#produtos-container');
    const cartItems = document.querySelector('#cartItems');
    const cartCount = document.getElementById('cart-count');
    const cartTotal = document.querySelector('#cartTotal');
    if (addCarrinho) {
        addCarrinho.addEventListener('click', function (event) {

            const containerProdutos = document.querySelector('#produtos-container');
            if (containerProdutos) {
                if (logadoCarrinho && !usuarioAutenticado) {
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
                                }
                            });
                        }
                    });
                    return;
                }
            }
            if (event.target.classList.contains('adicionar-carrinho')) {
                const buttonPrevent = event.target;
                if (buttonPrevent.disabled) return;
                buttonPrevent.disabled = true;
                const produtoSelecionado = event.target.closest('.card-produto');
                const nomeProduto = produtoSelecionado.querySelector('.card-title').textContent;
                const imagemProduto = produtoSelecionado.querySelector('img').src;
                const quantidadeDisponivel = parseInt(produtoSelecionado.querySelector('.produto-quantidade')?.textContent.replace(/\D/g, '')) || 0;

                let produtoExistente = Array.from(cartItems.querySelectorAll('tr')).find(row => {
                    return row.querySelector('td:nth-child(2)').textContent === nomeProduto;
                });
                let precoProduto;
                if (exibirPreco) {
                    precoProduto = parseFloat(produtoSelecionado.querySelector('.produto-preco').textContent.replace('R$', '').trim());

                }
                if (produtoExistente) {
                    const quantidadeSpan = produtoExistente.querySelector('.quantity-span');
                    let quantidadeAtual = parseInt(quantidadeSpan.textContent);
                    if (!validarQuantidade && quantidadeAtual >= quantidadeDisponivel) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Estoque insuficiente!',
                            text: `Você não pode adicionar mais do que ${quantidadeDisponivel} unidades.`,
                        });
                        return;
                    }
                    quantidadeAtual += 1;
                    quantidadeSpan.textContent = quantidadeAtual;
                    if (exibirPreco) {
                        const subtotalCell = produtoExistente.querySelector('.subtotal-cell');
                        const novoSubtotal = quantidadeAtual * precoProduto;
                        subtotalCell.textContent = `R$ ${novoSubtotal.toFixed(2)}`;
                    }
                } else {
                    if (!validarQuantidade && quantidadeDisponivel <= 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Produto fora de estoque!',
                            text: 'Este produto não está disponível no momento.',
                        });
                        return;
                    }
                    const imagemCardAtual = produtoSelecionado.querySelector('img').src;
                    const produtoCarrinho = document.createElement('tr');
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
                    if (exibirPreco) {
                        const precoCell = document.createElement('td');
                        precoCell.textContent = `R$ ${precoProduto.toFixed(2)}`;
                        produtoCarrinho.appendChild(precoCell);

                        const subtotalCell = document.createElement('td');
                        subtotalCell.textContent = `R$ ${precoProduto.toFixed(2)}`;
                        subtotalCell.classList.add('subtotal-cell')
                        produtoCarrinho.appendChild(subtotalCell);
                    }
                    const acao = document.createElement('td');
                    const botaoRemover = document.createElement('button');
                    botaoRemover.className = 'remover-item btn btn-danger';
                    botaoRemover.type = 'button';
                    const svg = document.createElement('svg');
                    svg.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="remover-item bi bi-trash" viewBox="0 0 16 16"><path class="remover-item"  d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/><path class="remover-item"  d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/></svg>';
                    botaoRemover.appendChild(svg);
                    acao.appendChild(botaoRemover);
                    produtoCarrinho.appendChild(acao);
                    cartItems.appendChild(produtoCarrinho);
                    var contagem = parseInt(document.getElementById("cart-count").textContent)
                    if (isNaN(contagem)) contagem = 0
                    contagem++
                    document.getElementById("cart-count").textContent = contagem
                    verificarBotaoFinalizar();
                }
                let quantidadeTotal = 0;
                let totalGeral = 0;
                let produtoParaAdicionar;
                if (exibirPreco) {
                    Array.from(cartItems.querySelectorAll('tr')).forEach(row => {
                        const quantidadeSpan = row.querySelector('.quantity-span');
                        const subtotalCell = row.querySelector('.subtotal-cell');
                        const quantidade = parseInt(quantidadeSpan.textContent);
                        const subtotal = parseFloat(subtotalCell.textContent.replace('R$', '').trim());

                        quantidadeTotal += quantidade;
                        totalGeral += subtotal;
                    });

                    cartCount.textContent = quantidadeTotal;
                    cartTotal.textContent = `R$ ${totalGeral.toFixed(2)}`;


                    produtoParaAdicionar = {
                        nome: nomeProduto,
                        imagem: produtoSelecionado.querySelector('img').src,
                        preco: precoProduto,
                        quantidade: 1
                    };
                } else {
                    Array.from(cartItems.querySelectorAll('tr')).forEach(row => {
                        const quantidadeSpan = row.querySelector('.quantity-span');
                        const quantidade = parseInt(quantidadeSpan.textContent);
                        quantidadeTotal += quantidade;
                    });

                    cartCount.textContent = quantidadeTotal;

                    produtoParaAdicionar = {
                        nome: nomeProduto,
                        imagem: produtoSelecionado.querySelector('img').src,
                        quantidade: 1
                    }
                }
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
            }

        });
        verificarBotaoFinalizar();
    }
});

const cartItems = document.getElementById('cartItems');
if (cartItems) {
    cartItems.addEventListener('click', function (event) {
        const button = event.target;
        const inputGrupo = button.closest('.input-group'); // Grupo de botões de quantidade
        if (inputGrupo) {
            const quantidadeSpan = inputGrupo.querySelector('.quantity-span');
            const item = button.closest('tr'); // Linha do item
            const nomeProduto = item.querySelector('td:nth-child(2)').textContent; // Nome do produto
            let value = parseInt(quantidadeSpan.textContent); // Quantidade atual
            let quantidadeAtual = parseInt(quantidadeSpan.textContent);

            const produtoOriginal = Array.from(document.querySelectorAll('.card-produto')).find(produto =>
                produto.querySelector('.card-title').textContent === nomeProduto
            );

            const quantidadeDisponivel = produtoOriginal
                ? parseInt(produtoOriginal.querySelector('.produto-quantidade')?.textContent.replace(/\D/g, '')) || 0
                : Infinity; // Caso não encontre, assume infinito (não restringe)


            // Botão de decremento
            if (button.classList.contains('button-minus')) {
                if (value > 1) {
                    value--;
                    quantidadeSpan.textContent = value;
                    atualizarProdutoQuantidade(nomeProduto, value); // Atualiza quantidade no estado
                    if (exibirPreco) atualizarSubtotal(item, value); // Atualiza o subtotal no DOM
                    var contagem = parseInt(document.getElementById("cart-count").textContent)
                    contagem--
                    document.getElementById("cart-count").textContent = contagem
                    verificarBotaoFinalizar();
                    let quantidadeTotal = 0;
                    let totalGeral = 0;
                    const cartTotal = document.querySelector('#cartTotal');
                    const cartCount = document.getElementById('cart-count');
                    if (exibirPreco) {
                        Array.from(cartItems.querySelectorAll('tr')).forEach(row => {
                            const quantidadeSpan = row.querySelector('.quantity-span');
                            const subtotalCell = row.querySelector('.subtotal-cell');
                            const quantidade = parseInt(quantidadeSpan.textContent);
                            const subtotal = parseFloat(subtotalCell.textContent.replace('R$', '').trim());

                            quantidadeTotal += quantidade;
                            totalGeral += subtotal;
                        });

                        cartCount.textContent = quantidadeTotal;
                        cartTotal.textContent = `R$ ${totalGeral.toFixed(2)}`;
                    } else {
                        Array.from(cartItems.querySelectorAll('tr')).forEach(row => {
                            const quantidadeSpan = row.querySelector('.quantity-span');
                            const quantidade = parseInt(quantidadeSpan.textContent);
                            quantidadeTotal += quantidade;
                        });

                        cartCount.textContent = quantidadeTotal;
                    }

                } else if (value === 1) {
                    removerProduto(item, nomeProduto); // Remove o item se a quantidade for 0
                    var contagem = parseInt(document.getElementById("cart-count").textContent)
                    contagem--
                    document.getElementById("cart-count").textContent = contagem
                    verificarBotaoFinalizar();
                    let quantidadeTotal = 0;
                    let totalGeral = 0;
                    const cartTotal = document.querySelector('#cartTotal');
                    const cartCount = document.getElementById('cart-count');
                    if (exibirPreco) {
                        Array.from(cartItems.querySelectorAll('tr')).forEach(row => {
                            const quantidadeSpan = row.querySelector('.quantity-span');
                            const subtotalCell = row.querySelector('.subtotal-cell');
                            const quantidade = parseInt(quantidadeSpan.textContent);
                            const subtotal = parseFloat(subtotalCell.textContent.replace('R$', '').trim());

                            quantidadeTotal += quantidade;
                            totalGeral += subtotal;
                        });

                        cartCount.textContent = quantidadeTotal;
                        cartTotal.textContent = `R$ ${totalGeral.toFixed(2)}`;
                    } else {
                        Array.from(cartItems.querySelectorAll('tr')).forEach(row => {
                            const quantidadeSpan = row.querySelector('.quantity-span');
                            const quantidade = parseInt(quantidadeSpan.textContent);
                            quantidadeTotal += quantidade;
                        });

                        cartCount.textContent = quantidadeTotal;
                    }
                    verificarBotaoFinalizar();
                }
            }

            // Botão de incremento
            if (button.classList.contains('button-plus')) {
                if (!validarQuantidade && quantidadeAtual >= quantidadeDisponivel) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Estoque insuficiente!',
                        text: `Você não pode adicionar mais do que ${quantidadeDisponivel} unidades.`,
                    });
                    return;
                }
                value++;
                quantidadeSpan.textContent = value;
                atualizarProdutoQuantidade(nomeProduto, value); // Atualiza quantidade no estado
                if (exibirPreco) atualizarSubtotal(item, value); // Atualiza o subtotal no DOM
                var contagem = parseInt(document.getElementById("cart-count").textContent)
                contagem++
                document.getElementById("cart-count").textContent = contagem
                verificarBotaoFinalizar();
                let quantidadeTotal = 0;
                let totalGeral = 0;
                const cartTotal = document.querySelector('#cartTotal');
                const cartCount = document.getElementById('cart-count');
                if (exibirPreco) {
                    Array.from(cartItems.querySelectorAll('tr')).forEach(row => {
                        const quantidadeSpan = row.querySelector('.quantity-span');
                        const subtotalCell = row.querySelector('.subtotal-cell');
                        const quantidade = parseInt(quantidadeSpan.textContent);
                        const subtotal = parseFloat(subtotalCell.textContent.replace('R$', '').trim());

                        quantidadeTotal += quantidade;
                        totalGeral += subtotal;
                    });

                    cartCount.textContent = quantidadeTotal;
                    cartTotal.textContent = `R$ ${totalGeral.toFixed(2)}`;
                } else {
                    Array.from(cartItems.querySelectorAll('tr')).forEach(row => {
                        const quantidadeSpan = row.querySelector('.quantity-span');
                        const quantidade = parseInt(quantidadeSpan.textContent);
                        quantidadeTotal += quantidade;
                    });

                    cartCount.textContent = quantidadeTotal;
                }
            }
        }

        // Remoção do item com o botão de ação
        if (button.classList.contains('remover-item')) {
            const item = button.closest('tr');
            const nomeProduto = item.querySelector('td:nth-child(2)').textContent;
            removerProduto(item, nomeProduto); // Remove o produto

            var contagem = parseInt(document.getElementById("cart-count").textContent)
            contagem--
            document.getElementById("cart-count").textContent = contagem
            verificarBotaoFinalizar();
            let quantidadeTotal = 0;
            let totalGeral = 0;
            const cartTotal = document.querySelector('#cartTotal');
            const cartCount = document.getElementById('cart-count');
            if (exibirPreco) {
                Array.from(cartItems.querySelectorAll('tr')).forEach(row => {
                    const quantidadeSpan = row.querySelector('.quantity-span');
                    const subtotalCell = row.querySelector('.subtotal-cell');
                    const quantidade = parseInt(quantidadeSpan.textContent);
                    const subtotal = parseFloat(subtotalCell.textContent.replace('R$', '').trim());

                    quantidadeTotal += quantidade;
                    totalGeral += subtotal;
                });

                cartCount.textContent = quantidadeTotal;
                cartTotal.textContent = `R$ ${totalGeral.toFixed(2)}`;
            } else {
                Array.from(cartItems.querySelectorAll('tr')).forEach(row => {
                    const quantidadeSpan = row.querySelector('.quantity-span');
                    const quantidade = parseInt(quantidadeSpan.textContent);
                    quantidadeTotal += quantidade;
                });

                cartCount.textContent = quantidadeTotal;
            }
            verificarBotaoFinalizar();
        }
    });
}
function verificarBotaoFinalizar() {
    if (logadoCarrinho && !usuarioAutenticado) return;

    const finalizarBotao = document.getElementById('finalizar');
    const contagem = parseInt(document.getElementById("cart-count").textContent)
    if (contagem === 0) {
        finalizarBotao.disabled = true;
    } else {
        finalizarBotao.disabled = false;
    }
}

function atualizarSubtotal(item, quantidade) {
    if (exibirPreco) {
        const preco = parseFloat(
            item.querySelector('td:nth-child(4)').textContent.replace('R$', '').trim()
        );
        const subtotalCell = item.querySelector('.subtotal-cell'); // Identifica a célula do subtotal
        const novoSubtotal = quantidade * preco; // Calcula o subtotal atualizado
        subtotalCell.textContent = `R$ ${novoSubtotal.toFixed(2)}`;
    }
}

function removerProduto(item, nomeProduto) {
    item.remove(); // Remove do DOM
    let produtosCarrinho = carregarProdutosCarrinho(); // Carrega o estado atual do carrinho
    produtosCarrinho = produtosCarrinho.filter(p => p.nome !== nomeProduto); // Remove o produto do estado
    atualizarCookiesCarrinho(produtosCarrinho); // Atualiza cookies
    atualizarContagemCarrinho(); // Atualiza contador
    verificarBotaoFinalizar();
}

function atualizarProdutoQuantidade(nomeProduto, quantidade) {
    const produtosCarrinho = carregarProdutosCarrinho();
    const produto = produtosCarrinho.find(p => p.nome === nomeProduto);
    if (produto) {
        produto.quantidade = quantidade;
    }
    if (quantidade === 0) {
        const index = produtosCarrinho.indexOf(produto);
        produtosCarrinho.splice(index, 1);
    }
    atualizarCookiesCarrinho(produtosCarrinho);
    atualizarContagemCarrinho();
}

async function atualizarCookiesCarrinho(produtos) {
    document.cookie = "carrinho=" + encodeURIComponent(JSON.stringify(produtos)) + "; path=/;";
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

    } catch (error) {
        console.error('Erro ao atualizar o carrinho:', error);
    }

}

function atualizarContagemCarrinho() {
    if (logadoCarrinho && !usuarioAutenticado) return;
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
        produto.preco = parseFloat(produto.preco) || 0;
        produtosCarrinho.push(produto);
    }

    atualizarCookiesCarrinho(produtosCarrinho);
}

function carregarProdutosCarrinho() {
    const carrinhoCookie = getCookie('carrinho');

    if (!carrinhoCookie) {
        return [];
    }

    try {
        const produtos = JSON.parse(carrinhoCookie);
        return produtos.map(produto => ({
            ...produto,
            preco: produto.preco ? parseFloat(produto.preco) : null,
            quantidade: parseInt(produto.quantidade) || 0
        }));
    } catch (error) {
        console.error("Erro ao carregar carrinho: Cookie inválido!", error);
        return [];
    }
}


function getCookie(nome) {
    const value = `; ${decodeURIComponent(document.cookie)}`;
    const parts = value.split(`; ${nome}=`);
    if (parts.length === 2) {
        return parts.pop().split(';').shift();
    }
    return null;
}

document.addEventListener('DOMContentLoaded', () => {
    const finalizarCompra = document.getElementById('finalizar');

    if (finalizarCompra) {
        finalizarCompra.addEventListener('click', function () {
            if (!usuarioAutenticado) {
                Swal.fire({
                    title: "Você está deslogado!",
                    text: "Você pode solicitar um orçamento pelo WhatsApp ou criar uma conta.",
                    icon: "info",
                    showCancelButton: true,
                    showCloseButton: true,
                    confirmButtonText: 'Solicitar orçamento <i class="fa-brands fa-whatsapp"></i>',
                    cancelButtonText: "Criar conta",
                    customClass: {
                        confirmButton: 'btn btn-success button-success',
                        cancelButton: 'btn btn-primary button-primary'
                    },
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        const produtosCarrinho = carregarProdutosCarrinho() || [];
                        let mensagem = "Olá, gostaria de solicitar um orçamento para os seguintes produtos:\n\n";
                        produtosCarrinho.forEach(produto => {
                            mensagem += `📦 ${produto.nome} - ${produto.quantidade}x (${produto.preco})\n`;
                        });

                        const numeroWhatsApp = "5567996228134";
                        const urlWhatsApp = `https://api.whatsapp.com/send?phone=${numeroWhatsApp}&text=${encodeURIComponent(mensagem)}`;

                        window.open(urlWhatsApp, '_blank');
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        window.location.href = "/register";
                    }
                });
                return;
            }
            const produtosCarrinho = carregarProdutosCarrinho();
            const produtosFormatados = produtosCarrinho.map(produto => ({
                id: produto.id,
                quantidade: produto.quantidade,
                preco: exibirPreco ? produto.preco : 0
            }));
            fetch(`/registrar/venda`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ produtos: produtosFormatados }),
            })
                .then(response => {

                    if (response.status === 403) {
                        return response.json().then(data => {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Informações Incompletas',
                                text: data.message || 'Você precisa completar seu endereço antes de finalizar a compra.',
                                confirmButtonText: 'Ir para o perfil',
                            }).then(() => {
                                window.location.href = data.redirect_url;
                            });
                        });
                    }
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.message || 'Erro desconhecido');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.status === 'success') {
                        Swal.fire({
                            icon: "success",
                            title: "Compra finalizada!",
                            text: "Seu pedido já foi enviado, logo um dos nossos colaboradores entrará em contato com você!",
                            showClass: {
                                popup: `animate__rubberBand animate__backOutUp`,
                            },
                            hideClass: {
                                popup: `animate__backOutDown`,
                            },
                        });
                        limparCarrinho();
                        const carrinhoModal = document.getElementById('modal-loja');
                        if (carrinhoModal) {
                            carrinhoModal.classList.remove('show');
                            carrinhoModal.style.display = 'none';
                        }

                        const modalBackdrop = document.querySelector('.modal-backdrop');
                        if (modalBackdrop) modalBackdrop.remove();

                        document.body.classList.remove('modal-open');
                        document.body.style.removeProperty('padding-right');
                        document.body.style.removeProperty('overflow');
                    }
                })
                .catch(error => {
                    console.error('Erro na requisição:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: error.message || 'Não foi possível processar sua solicitação. Tente novamente.',
                    });
                });
        });
    }
});

function limparCarrinho() {
    document.cookie = "carrinho=" + encodeURIComponent(JSON.stringify([])) + "; path=/;";

    const cartItems = document.querySelector('#cartItems');
    cartItems.innerHTML = '';
    atualizarContagemCarrinho();
}

const limpaBotao = document.getElementById('limpar-tudo');
if (limpaBotao) {
    limpaBotao.addEventListener('click', function (event) {
        limparCarrinho();
        atualizarCarrinho();
    });
}
window.addEventListener('pageshow', function (event) {
    atualizarCarrinho();
    atualizarContagemCarrinho();
});

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
    const linksAdd = document.querySelectorAll('.text-decoration-none a-text');
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
        if (window.innerWidth < 1300) {
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
    footerResponse()
});

window.addEventListener('load', () => {
    checkScreenSize();
    adjustLayout();
    footerResponse()
});

window.dispatchEvent(new Event('resize'));

function footerResponse() {
    let img = document.getElementById('imagemTelaMaior');
    let texto = document.getElementById('textoTelaMenor');
    if (img && texto) {

        if (window.innerWidth < 604) {
            img.classList.add('d-none');
            texto.classList.remove('d-none');

        } else {
            img.classList.remove('d-none');
            texto.classList.add('d-none');
        }
    }
}

//CATEGORIA
const grupoTodos = document.querySelector('[data-grupo-id="todos"]');
grupoTodos.addEventListener('click', function (event) {
    event.preventDefault();
    atualizarGruposOuSubgrupos({ grupoId: 'todos' });
});

async function atualizarGruposOuSubgrupos({ grupoId = '', subgrupoId = '' }) {
    const produtosContainer = document.getElementById('produtos-container');
    const subgruposList = grupoId ? document.getElementById(`subgrupos-${grupoId}`) : null;
    const grupoAtivo = document.querySelector('.list-group-item.active');
    if (grupoAtivo && grupoAtivo.getAttribute('data-grupo-id') === grupoId && !subgrupoId) {
        grupoAtivo.classList.remove('active');
        const subgrupoAtivoAnterior = document.querySelector('.subgrupo-item.active');
        if (subgrupoAtivoAnterior) {
            subgrupoAtivoAnterior.classList.remove('active');
        }
        document.querySelectorAll('.subgrupos-list').forEach(list => list.classList.add('d-none'));
        buscarProdutos({ pesquisa: '', grupo: '', subgrupo: '', limite: 12, tipo_chamada: 'nova_busca', escopo: 'todos' });
        document.querySelector('[data-grupo-id="todos"]').classList.add('active');
        return;
    }
    if (!subgrupoId) {
        document.querySelectorAll('.grupo-item').forEach(item => item.classList.remove('active'));
        document.querySelector(`[data-grupo-id="${grupoId}"]`)?.classList.add('active');
    }

    document.querySelectorAll('.subgrupos-list').forEach(list => {
        if (!list.id.includes(`subgrupos-${grupoId}`)) {
            list.classList.add('d-none');
        }
    });
    if (grupoId && !subgrupoId) {
        const subgrupoAtivoAnterior = document.querySelector('.subgrupo-item.active');
        if (subgrupoAtivoAnterior) {
            subgrupoAtivoAnterior.classList.remove('active');
        }
        if (grupoId === 'todos') {
            buscarProdutos({ pesquisa: '', grupo: '', subgrupo: '', limite: 12, tipo_chamada: 'nova_busca', escopo: 'todos' });
            return;
        }

        if (subgruposList) {
            subgruposList.classList.remove('d-none');
            subgruposList.innerHTML = '<li class="list-group-item m-0">Carregando...</li>';

            try {
                const response = await fetch(`/buscar-subgrupos`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({ grupo: grupoId }),
                });

                const data = await response.json();
                if (data.status === 'sucesso' && Array.isArray(data.subgrupos)) {
                    subgruposList.innerHTML = data.subgrupos
                        .map(subgrupo => `
                            <a href="" class=" ml-3 list-group-item list-group-item-action subgrupo-item" data-subgrupo-id="${subgrupo.codigo}">
                                ${subgrupo.descricao}
                            </a>
                        `)
                        .join('');
                } else {
                    subgruposList.innerHTML = '<li class="list-group-item">Nenhum subgrupo encontrado</li>';
                }
            } catch (error) {
                console.error('Erro ao buscar subgrupos:', error);
                subgruposList.innerHTML = '<li class="list-group-item">Erro ao carregar subgrupos</li>';
            }
        }

        buscarProdutos({ pesquisa: '', grupo: grupoId, subgrupo: '', limite: 12, tipo_chamada: 'nova_busca', escopo: 'grupo' });
    }

    if (grupoId && subgrupoId) {
        document.querySelector(`[data-grupo-id="${grupoId}"]`)?.classList.add('active');

        const subgrupoAtivoAnterior = document.querySelector('.subgrupo-item.active');
        if (subgrupoAtivoAnterior) {
            subgrupoAtivoAnterior.classList.remove('active');
        }

        const subgrupoAtivo = document.querySelector(`[data-subgrupo-id="${subgrupoId}"]`);
        if (subgrupoAtivo) {
            setTimeout(() => {
                subgrupoAtivo.classList.add('active');
            }, 100);
        }

        buscarProdutos({ pesquisa: '', grupo: grupoId, subgrupo: subgrupoId, limite: 12, tipo_chamada: 'nova_busca', escopo: 'subgrupo' });
    }
}
document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('click', event => {
        if (event.target.classList.contains('grupo-item')) {
            event.preventDefault();
            const grupoId = event.target.getAttribute('data-grupo-id');
            atualizarGruposOuSubgrupos({ grupoId });
        }
        else if (event.target.classList.contains('subgrupo-item')) {
            event.preventDefault();
            const subgrupoId = event.target.getAttribute('data-subgrupo-id');
            const grupoId = document.querySelector('.list-group-item.active')?.getAttribute('data-grupo-id');
            atualizarGruposOuSubgrupos({ grupoId, subgrupoId });
        }
    });
});
document.addEventListener('DOMContentLoaded', () => {
    const toggleButton = document.getElementById('toggleGrupos1');
    const gruposOcultos = document.querySelectorAll('.oculto');

    toggleButton.addEventListener('click', () => {
        const areHidden = Array.from(gruposOcultos).some(grupo => grupo.classList.contains('d-none'));

        if (areHidden) {

            gruposOcultos.forEach(grupo => grupo.classList.remove('d-none'));
            toggleButton.textContent = 'Mostrar menos';
        } else {
            gruposOcultos.forEach(grupo => grupo.classList.add('d-none'));
            toggleButton.textContent = 'Mostrar mais';
        }
    });
});


//AVISE-ME
document.addEventListener("click", function (event) {
    if (event.target.classList.contains("avise-me")) {
        const produtoId = event.target.dataset.id;

        Swal.fire({
            title: "Informe seu e-mail",
            input: "email",
            inputPlaceholder: "Digite seu e-mail para ser notificado",
            showCancelButton: true,
            confirmButtonText: "Cadastrar",
            preConfirm: (email) => {
                if (!email) {
                    Swal.showValidationMessage("Por favor, informe um e-mail válido.");
                }
                return email;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("/notificar-produto", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    },
                    body: JSON.stringify({
                        produto_id: produtoId,
                        email: result.value,
                    }),
                })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire({
                            icon: "success",
                            title: "Sucesso!",
                            text: data.mensagem,
                        });
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: "error",
                            title: "Erro!",
                            text: "Ocorreu um erro ao cadastrar seu e-mail. Tente novamente.",
                        });
                    });
            }
        });
    }
});

//ZIPZAP
function enviarOrcamentoWhatsApp() {
    const produtosCarrinho = carregarProdutosCarrinho() || [];

    if (produtosCarrinho.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Carrinho vazio',
            text: 'Adicione produtos antes de solicitar um orçamento!',
        });
        return;
    }

    let mensagem = "Olá, gostaria de solicitar um orçamento para os seguintes produtos:\n\n";
    produtosCarrinho.forEach(produto => {
        mensagem += `📦 ${produto.nome} - ${produto.quantidade}x (${produto.preco})\n`;
    });

    const numeroWhatsApp = "5567996228134";
    const urlWhatsApp = `https://api.whatsapp.com/send?phone=${numeroWhatsApp}&text=${encodeURIComponent(mensagem)}`;

    window.open(urlWhatsApp, '_blank');
}

var zap = document.getElementById('orcamento')
if (zap) {
    zap.addEventListener('click', (event) => {
        enviarOrcamentoWhatsApp();
    });
}