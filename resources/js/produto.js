import Swal from 'sweetalert2';
var usuarioAutenticado = document.getElementById('usuario-autenticado').dataset.autenticado === 'true';
var exibirPreco = document.body.dataset.exibirPreco === 'true';
var validarQuantidade = document.getElementById('validar-estoque').dataset.estoque === 'true';
var logadoCarrinho = document.getElementById('logado-carrinho').dataset.carrinho === 'true';
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
    verificarBotaoFinalizar();
}
const addCarrinho1 = document.getElementById('adicionar-carrinho');
if (addCarrinho1) {
    addCarrinho1.addEventListener('click', async function (event) {
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
            return;
        }
        const produto = {
            id: this.dataset.id,
            nome: document.getElementById('nome-produto').textContent,
            imagem: document.getElementById('imagem').src,
            preco: exibirPreco
                ? parseFloat(document.querySelector('.produto-preco').textContent.replace('Preço: R$', '').trim()) || 0
                : undefined,
            quantidade: 1
        };

        const produtosCarrinho = carregarProdutosCarrinho();
        const produtoExistente = produtosCarrinho.find(p => p.nome === produto.nome);
        if (produtoExistente) {
            const quantidadeDisponivel = parseInt(document.querySelector('.produto-quantidade')?.textContent.replace(/\D/g, '')) || 0;
            if (validarQuantidade && produtoExistente.quantidade >= quantidadeDisponivel) {
                Swal.fire({
                    icon: 'error',
                    title: 'Estoque insuficiente!',
                    text: `Você não pode adicionar mais do que ${quantidadeDisponivel} unidades.`,
                });
                return;
            }
            produtoExistente.quantidade += 1;
        } else {
            produtosCarrinho.push(produto);
        }
        await atualizarCookiesCarrinho(produtosCarrinho);
        atualizarCarrinho();
        atualizarContagemCarrinho();
        Swal.fire({
            icon: 'success',
            title: 'Produto adicionado ao carrinho!',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        });
    });
}

function atualizarCarrinho() {
    if (logadoCarrinho && !usuarioAutenticado) return;
    const produtosCarrinho = carregarProdutosCarrinho();
    const cartItems = document.querySelector('#cartItems');

    cartItems.innerHTML = '';
    let total = 0;

    produtosCarrinho.forEach(produto => {
        const produtoCarrinho = document.createElement('tr');
        const imagemContainer = document.createElement('td');
        const imagem = document.createElement('img');
        imagem.src = produto.imagem;
        imagem.style.width = "15vh";
        imagemContainer.appendChild(imagem);
        produtoCarrinho.appendChild(imagemContainer);

        const nomeContainer = document.createElement('td');
        nomeContainer.textContent = produto.nome;
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
        quantidadeSpan.textContent = produto.quantidade;

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
            produtoCarrinho.appendChild(precoCell);
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
        const svg = document.createElement('svg');
        svg.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="remover-item bi bi-trash" viewBox="0 0 16 16"><path class="remover-item" d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/><path class="remover-item" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/></svg>';
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
});

const cartItems = document.getElementById('cartItems');
if (cartItems) {
    cartItems.addEventListener('click', function (event) {
        const button = event.target;
        const inputGrupo = button.closest('.input-group');
        if (inputGrupo) {
            const quantidadeSpan = inputGrupo.querySelector('.quantity-span');
            const item = button.closest('tr');
            const nomeProduto = item.querySelector('td:nth-child(2)').textContent;
            let value = parseInt(quantidadeSpan.textContent);
            const quantidadeDisponivel = parseInt(document.querySelector('.produto-quantidade')?.textContent.replace(/\D/g, '')) || 0;
            if (button.classList.contains('button-minus')) {
                if (value > 1) {
                    value--;
                    quantidadeSpan.textContent = value;
                    atualizarProdutoQuantidade(nomeProduto, value);
                } else {
                    item.remove();
                    let produtosCarrinho = carregarProdutosCarrinho();
                    produtosCarrinho = produtosCarrinho.filter(p => p.nome !== nomeProduto);
                    atualizarCookiesCarrinho(produtosCarrinho);
                }
                atualizarCarrinho();
                atualizarContagemCarrinho();
            }

            if (button.classList.contains('button-plus')) {
                if (!validarQuantidade && value >= quantidadeDisponivel) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Estoque insuficiente!',
                        text: `Você não pode adicionar mais do que ${quantidadeDisponivel} unidades.`,
                    });
                    return;
                }
                value++;
                quantidadeSpan.textContent = value;
                atualizarProdutoQuantidade(nomeProduto, value);
                atualizarCarrinho();
                atualizarContagemCarrinho();
            }
        }

        if (button.classList.contains('remover-item')) {
            const item = button.closest('tr');
            const nomeProduto = item.querySelector('td:nth-child(2)').textContent;
            item.remove();
            let produtosCarrinho = carregarProdutosCarrinho();
            produtosCarrinho = produtosCarrinho.filter(p => p.nome !== nomeProduto);
            atualizarCookiesCarrinho(produtosCarrinho);
            atualizarCarrinho();
            atualizarContagemCarrinho();
        }
    });
}
function verificarBotaoFinalizar() {
    if (logadoCarrinho && !usuarioAutenticado) return;
    const finalizarBotao = document.getElementById('finalizar');
    const produtosCarrinho = carregarProdutosCarrinho();
    if (produtosCarrinho.length === 0) {
        finalizarBotao.disabled = true;
    } else {
        finalizarBotao.disabled = false;
    }
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
function atualizarProdutoQuantidade(nomeProduto, quantidade) {
    const produtosCarrinho = carregarProdutosCarrinho();
    const produto = produtosCarrinho.find(p => p.nome === nomeProduto);
    if (produto) {
        produto.quantidade = quantidade;
    }
    atualizarCookiesCarrinho(produtosCarrinho);
}

function adicionarProdutoCarrinho(produto) {
    const produtosCarrinho = carregarProdutosCarrinho();
    const produtoExistente = produtosCarrinho.find(p => p.nome === produto.nome);
    const quantidadeDisponivel = parseInt(document.querySelector('.produto-quantidade')?.textContent.replace(/\D/g, '')) || 0;

    if (produtoExistente) {
        if (!validarQuantidade && produtoExistente.quantidade >= quantidadeDisponivel) {
            Swal.fire({
                icon: 'error',
                title: 'Estoque insuficiente!',
                text: `Você não pode adicionar mais do que ${quantidadeDisponivel} unidades.`,
            });
            return;
        }
        produtoExistente.quantidade += 1;
    } else {
        if (exibirPreco) {
            const novoProduto = {
                id: produto.id,
                nome: produto.nome,
                imagem: produto.imagem,
                quantidade: produto.quantidade || 1,
                preco: parseFloat(produto.preco)
            };
            produtosCarrinho.push(novoProduto);
        } else {
            const novoProduto = {
                id: produto.id,
                nome: produto.nome,
                imagem: produto.imagem,
                quantidade: produto.quantidade || 1,
            };
            produtosCarrinho.push(novoProduto);
        }
    }

    atualizarCookiesCarrinho(produtosCarrinho);
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
const limpaBotao = document.getElementById('limpar-tudo');
if (limpaBotao) {
    limpaBotao.addEventListener('click', function () {
        document.cookie = "carrinho=" + encodeURIComponent(JSON.stringify([])) + "; path=/;";
        atualizarCarrinho();
        atualizarContagemCarrinho();
    });
}
function limparCarrinho() {
    document.cookie = "carrinho=" + encodeURIComponent(JSON.stringify([])) + "; path=/;";

    const cartItems = document.querySelector('#cartItems');
    cartItems.innerHTML = '';
    atualizarContagemCarrinho();
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

// TEXT FOOTER FUNCTION

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
window.addEventListener('resize', () => {
    footerResponse()
});

window.addEventListener('load', () => {
    footerResponse()
});

document.addEventListener("DOMContentLoaded", function () {
    const imagemPrincipal = document.getElementById("imagem");
    const miniaturas = document.querySelectorAll(".thumbnail");

    miniaturas.forEach(thumbnail => {
        thumbnail.addEventListener("mouseover", function () {
            const novaImagem = thumbnail.src;
            imagemPrincipal.src = novaImagem;
        });
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
