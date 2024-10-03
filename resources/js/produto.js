import Swal from 'sweetalert2';
var usuarioAutenticado = document.getElementById('usuario-autenticado').dataset.autenticado === 'true';

if (usuarioAutenticado) {
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
                    atualizarProdutoQuantidade(produtoNome, value - 1);
                }
            }

            if (button.classList.contains('button-plus')) {
                let value = parseInt(quantidadeSpan.textContent);
                quantidadeSpan.textContent = value + 1;
                atualizarProdutosCarrinho();
                atualizarProdutoQuantidade(produtoNome, value + 1);
            }
        }

        if (button.classList.contains('remover-item')) {
            const item = button.closest('tr');
            item.remove();
            let produtosCarrinho = carregarProdutosCarrinho();
            produtosCarrinho = produtosCarrinho.filter(p => p.nome !== nomeProduto);  //  Filtra o nome do produto removido e não adiciona ele na variavel.
            atualizarCookiesCarrinho(produtosCarrinho); //Atualiza os produtos do cookie sem o produto removido
            atualizarProdutosCarrinho();
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



    document.addEventListener('DOMContentLoaded', function () {
        atualizarContagemCarrinho();
    });

    function atualizarContagemCarrinho() {
        const produtosCarrinho = carregarProdutosCarrinho();
        const quantidadeTotal = produtosCarrinho.reduce((total, produto) => total + produto.quantidade, 0);
        document.getElementById("cart-count").innerText = quantidadeTotal;
    }

    document.addEventListener('DOMContentLoaded', function () {
        window.abrirFecharDropDown = function (escopo) {
            var dropdown = document.getElementById('drop');
            //var dropdownInstancia = new bootstrap.Dropdown(dropdown.querySelector('.dropdown-toggle'));
            if (escopo == "enter") {
                dropdown.setAttribute('data-bs-popper', 'static');
                dropdown.classList.add('show');
            } else if (escopo == "leave") {
                dropdown.removeAttribute('data-bs-popper');
                dropdown.classList.remove('show');
            }
        }
    });
}