import Swal from 'sweetalert2';
let rotaAtual = document.getElementById('rota').getAttribute('data-id');
async function buscar(pesquisa = '', escopo = '') {
    let url = '';
    if (escopo === 'usuarios') {
        url = '/pesquisar/usuarios';
    } else if (escopo === 'produtos') {
        url = '/pesquisar/produtos';
    } else {
        console.error('Escopo Inválido!');
        return;
    }

    const resposta = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ pesquisa: pesquisa })
    });

    const textoResposta = await resposta.json();
    const tabelaContainer = document.querySelector('tbody');
    const pagination = document.getElementById('paginacao');

    if (textoResposta.status === 'sucesso') {
        let itens;
        if (escopo === 'usuarios') {
            itens = textoResposta.usuarios;
        } else if (escopo === 'produtos') {
            itens = textoResposta.produtos;
        }

        const quantidade = itens.length;
        tabelaContainer.innerHTML = '';

        if (quantidade < 20) {
            pagination.classList.remove('d-flex');
            pagination.classList.add('d-none');
        } else {
            pagination.classList.remove('d-none');
            pagination.classList.add('d-flex');
        }

        if (quantidade === 0) {
            tabelaContainer.innerHTML = `<tr><td colspan="5">${escopo.charAt(0).toUpperCase() + escopo.slice(1, -1)} não encontrado!</td></tr>`;
        } else {
            itens.forEach(item => {
                const row = document.createElement('tr');
                row.classList.add('table-row');

                if (escopo === 'usuarios') {
                    row.innerHTML = `
                        <td>${item.id}</td>
                        <td>${item.name}</td>
                        <td>${item.email}</td>
                        <td>${item.cpf_cnpj}</td>
                        <td>
                            <form style="display:inline" action="/usuarios/${item.id}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir o acesso deste usuário?');">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-outline-danger" id="excluir">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="dark" class="bi bi-trash3" viewBox="0 0 16 16">
                                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    `;
                } else if (escopo === 'produtos'){
                    // Verificar se a imagem do produto está disponível ou usar o placeholder
                    const imagemProduto = item.imagem;

                    row.innerHTML = `
                        <td>${item.id}</td>
                        <td>
                            <img src="/storage/${imagemProduto}" style="width: 8rem; height: auto;" alt="Imagem do Produto">
                        </td>
                        <td>${item.nome}</td>
                        <td>${item.quantidade !== undefined ? item.quantidade : 'N/A'}</td>
                        <td>
                            <a href="/produtos/${item.id}/edit" class="btn btn-outline-dark" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                </svg>
                            </a>
                            <form style="display:inline" action="/produtos/${item.id}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-outline-danger" id="excluir">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="dark" class="bi bi-trash3" viewBox="0 0 16 16">
                                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    `;
                }

                tabelaContainer.appendChild(row);
            });
        }

    } else {
        Swal.fire({
            title: textoResposta.message,
            icon: 'error',
            confirmButtonText: 'Fechar'
        });
    }
}

// Pesquisa
const searchBtn = document.getElementById('pesquisar');
if (searchBtn) {
    searchBtn.addEventListener('click', (event) => {
        event.preventDefault();
        const pesquisaInput = document.querySelector('input[name="pesquisa"]').value;
        const escopo = document.querySelector('input[name="escopo"]').value;
        buscar(pesquisaInput, escopo);
    });
}

//SWEETALERT APOS ATUALIZAÇAO
document.addEventListener('DOMContentLoaded', function () {
    const successMessage = document.getElementById('success-message');

    if (successMessage) {
        Swal.fire({
            title: 'Sucesso!',
            text: successMessage.innerText,
            icon: 'success',
            confirmButtonText: 'Ok'
        });
    }
});

//COLOR PICKER
if (rotaAtual === 'configuracoes.edit') {
    const colorPicker = document.getElementById('colorPicker');
    const textInput = document.getElementById('value');
    function colorToHex(color) {
        const ctx = document.createElement("canvas").getContext("2d");
        ctx.fillStyle = color;
        const hexColor = ctx.fillStyle;

        if (hexColor.startsWith('#')) {
            return hexColor;
        }
        return null;
    }

    colorPicker.addEventListener('input', function () {
        textInput.value = colorPicker.value;
    });

    textInput.addEventListener('input', function () {
        const hexColor = colorToHex(textInput.value);
        if (hexColor) {
            colorPicker.value = hexColor;
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        const hexColor = colorToHex(textInput.value);
        if (hexColor) {
            colorPicker.value = hexColor;
        }
    });
}else if(rotaAtual==='configuracoes'){
    function parseColor(color) {
        const ctx = document.createElement("canvas").getContext("2d");
        ctx.fillStyle = color;
        const computedColor = ctx.fillStyle;
        if (color.startsWith("rgba")) {
            const rgbaValues = color.match(/\d+/g);
            return `rgb(${rgbaValues[0]}, ${rgbaValues[1]}, ${rgbaValues[2]})`;
        }
        return computedColor;
    }
    function calculateLuminance(color) {
        const rgbColor = parseColor(color).substring(1);
        const r = parseInt(rgbColor.substring(0, 2), 16) / 255;
        const g = parseInt(rgbColor.substring(2, 4), 16) / 255;
        const b = parseInt(rgbColor.substring(4, 6), 16) / 255;
        const luminance = 0.2126 * r + 0.7152 * g + 0.0722 * b;
        return luminance;
    }
    function setContrastingTextColor() {
        document.querySelectorAll('.color-cell').forEach(cell => {
            const bgColor = cell.getAttribute('data-color');
            const luminance = calculateLuminance(bgColor);
            cell.style.color = luminance > 0.5 ? '#000000' : '#ffffff';
        });
    }
    document.addEventListener('DOMContentLoaded', setContrastingTextColor);
}
