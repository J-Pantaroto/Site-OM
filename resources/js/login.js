import Swal from 'sweetalert2';
let camposValidos = {
    nome: true,
    email: true,
    cpfCnpj: true,
    senha: true,
    confirmarSenha: true,
};

let rotaAtual = document.getElementById('rota').getAttribute('data-id');
// Função geral para verificar se o formulário é válido
function verificarFormulario() {
    return Object.values(camposValidos).every(Boolean); // Retorna true se todos os campos forem válidos
}

// VALIDACOES EMAIL
document.getElementById('email').addEventListener('input', function () {
    const email = this;
    const emailDigitado = email.value;
    const mensagem = document.getElementById('erro-email');
    this.classList.remove('valido', 'invalido');
    mensagem.textContent = '';
    if (validarEmailFormato(emailDigitado)) {
        email.classList.remove('invalido');
        email.classList.add('valido');
        camposValidos.email = true;
        mensagem.textContent = '';
    } else {
        email.classList.remove('valido');
        email.classList.add('invalido');
        mensagem.style.color = 'red';
        mensagem.textContent = 'Email inválido';
        camposValidos.email = false;
    }
});

function validarEmailFormato(email) {

    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

if (rotaAtual === "register") {
    // Formatação do CPF e CNPJ
    document.getElementById('cpf_cnpj').addEventListener('input', function () {
        let cpf_cnpj = this.value.replace(/[^\d]+/g, ''); // Remove tudo que não for dígito

        if (cpf_cnpj.length <= 11) {
            cpf_cnpj = cpf_cnpj.replace(/(\d{3})(\d)/, '$1.$2');
            cpf_cnpj = cpf_cnpj.replace(/(\d{3})(\d)/, '$1.$2');
            cpf_cnpj = cpf_cnpj.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        } else if (cpf_cnpj.length <= 14) {
            cpf_cnpj = cpf_cnpj.replace(/^(\d{2})(\d)/, '$1.$2');
            cpf_cnpj = cpf_cnpj.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
            cpf_cnpj = cpf_cnpj.replace(/\.(\d{3})(\d)/, '.$1/$2');
            cpf_cnpj = cpf_cnpj.replace(/(\d{4})(\d{1,2})$/, '$1-$2');
        }
        this.value = cpf_cnpj; // Atualiza o valor do campo com a formatação
    });

    // Validação do CPF/CNPJ ao sair do campo
    document.getElementById('cpf_cnpj').addEventListener('change', function () {
        const mensagem = document.getElementById('erro-cpf-cnpj');
        this.classList.remove('valido', 'invalido');
        mensagem.textContent = '';

        let cpf_cnpj = this.value.replace(/[^\d]+/g, ''); // Remove tudo que não for dígito

        if (cpf_cnpj.length === 11) {
            // Validação do CPF
            if (/^(\d)\1{10}$/.test(cpf_cnpj)) {
                this.classList.remove('valido');
                this.classList.add('invalido');
                mensagem.style.color = 'red';
                mensagem.textContent = 'CPF inválido';
                camposValidos.cpfCnpj = false;
                return;
            }

            let soma = 0;
            for (let i = 0; i < 9; i++) {
                soma += parseInt(cpf_cnpj.charAt(i)) * (10 - i);
            }
            let primeiroDigito = 11 - (soma % 11);
            if (primeiroDigito === 10 || primeiroDigito === 11) {
                primeiroDigito = 0;
            }
            if (primeiroDigito !== parseInt(cpf_cnpj.charAt(9))) {
                this.classList.remove('valido');
                this.classList.add('invalido');
                mensagem.style.color = 'red';
                mensagem.textContent = 'CPF inválido';
                camposValidos.cpfCnpj = false;
                return;
            }

            soma = 0;
            for (let i = 0; i < 10; i++) {
                soma += parseInt(cpf_cnpj.charAt(i)) * (11 - i);
            }
            let segundoDigito = 11 - (soma % 11);
            if (segundoDigito === 10 || segundoDigito === 11) {
                segundoDigito = 0;
            }
            if (segundoDigito !== parseInt(cpf_cnpj.charAt(10))) {
                this.classList.remove('valido');
                this.classList.add('invalido');
                mensagem.style.color = 'red';
                mensagem.textContent = 'CPF inválido';
                camposValidos.cpfCnpj = false;
                return;
            }

            this.classList.remove('invalido');
            this.classList.add('valido');
            camposValidos.cpfCnpj = true;
            mensagem.textContent = '';
        } else if (cpf_cnpj.length === 14) {
            // Validação do CNPJ
            if (/^(\d)\1{13}$/.test(cpf_cnpj)) {
                this.classList.remove('valido');
                this.classList.add('invalido');
                mensagem.style.color = 'red';
                mensagem.textContent = 'CNPJ inválido';
                camposValidos.cpfCnpj = false;
                return;
            }

            let tamanho = cpf_cnpj.length - 2;
            let numeros = cpf_cnpj.substring(0, tamanho);
            let digitos = cpf_cnpj.substring(tamanho);
            let soma = 0;
            let pos = tamanho - 7;

            for (let i = tamanho; i >= 1; i--) {
                soma += numeros.charAt(tamanho - i) * pos--;
                if (pos < 2) {
                    pos = 9;
                }
            }

            let resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);

            if (resultado !== parseInt(digitos.charAt(0))) {
                this.classList.remove('valido');
                this.classList.add('invalido');
                mensagem.style.color = 'red';
                mensagem.textContent = 'CNPJ inválido';
                camposValidos.cpfCnpj = false;
                return;
            }

            tamanho = tamanho + 1;
            numeros = cpf_cnpj.substring(0, tamanho);
            soma = 0;
            pos = tamanho - 7;

            for (let i = tamanho; i >= 1; i--) {
                soma += numeros.charAt(tamanho - i) * pos--;
                if (pos < 2) {
                    pos = 9;
                }
            }

            resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);

            if (resultado !== parseInt(digitos.charAt(1))) {
                this.classList.remove('valido');
                this.classList.add('invalido');
                mensagem.style.color = 'red';
                mensagem.textContent = 'CNPJ inválido';
                camposValidos.cpfCnpj = false;
                return;
            }

            this.classList.remove('invalido');
            this.classList.add('valido');
            mensagem.textContent = '';
            camposValidos.cpfCnpj = true;
        } else {
            this.classList.remove('valido');
            this.classList.add('invalido');
            mensagem.style.color = 'red';
            mensagem.textContent = 'Número inválido';
            camposValidos.cpfCnpj = false;
        }
    });

    //Validar se o nome é coerente
    document.getElementById('name').addEventListener('input', function () {
        const mensagem = document.getElementById('erro-nome');
        mensagem.textContent = '';
        const nome = this;
        let nomeDigitado = nome.value;
        nome.classList.remove('invalido');
        nomeDigitado = nomeDigitado.trim();
        if (nomeDigitado.length < 3) {
            mensagem.style.color = 'red';
            mensagem.textContent = 'Informe um nome válido.';
            nome.classList.add('invalido');
            camposValidos.nome = false;
            return;
        }
        const nomeValido = /^[A-Za-zÀ-ÿ\s]+$/.test(nomeDigitado);
        if (!nomeValido) {
            mensagem.style.color = 'red';
            mensagem.textContent = 'O nome deve conter apenas letras.';
            nome.classList.add('invalido');
            camposValidos.nome = false;
            return;
        }
        const nomeRepetido = /(.)\1{2,}/.test(nomeDigitado);
        if (nomeRepetido) {
            mensagem.style.color = 'red';
            mensagem.textContent = 'O nome contém muitos caracteres repetidos.';
            nome.classList.add('invalido');
            camposValidos.nome = false;
            return;
        }
        this.classList.remove('invalido');
        this.classList.add('valido');
        camposValidos.nome = true;
        mensagem.textContent = '';
    });
}
if (rotaAtual === "register") {
    // VALIDACOES SENHA REGISTRO
    document.getElementById('password').addEventListener('input', function () {
        const mensagem = document.getElementById('erro-senha');
        this.classList.remove('valido', 'invalido');
        mensagem.textContent = '';
        let password = this.value;

        if (password.length < 8) {
            this.classList.remove('valido');
            this.classList.add('invalido');
            camposValidos.senha = false;
            mensagem.style.color = 'red';
            mensagem.textContent = 'Senha muito curta, informe uma senha com pelo menos 8 digitos';
            return;
        }
        if (!/[A-Z]/.test(password)) {
            this.classList.remove('valido');
            this.classList.add('invalido');
            mensagem.style.color = 'red';
            mensagem.textContent = 'Informe pelo menos uma letra maiuscula';
            camposValidos.senha = false;
            return;
        }
        if (!/[a-z]/.test(password)) {
            this.classList.remove('valido');
            this.classList.add('invalido');
            mensagem.style.color = 'red';
            mensagem.textContent = 'Informe pelo menos uma letra minuscula';
            camposValidos.senha = false;
            return;
        }

        // Senha valida
        this.classList.remove('invalido');
        this.classList.add('valido');
        camposValidos.senha = true;
        mensagem.textContent = '';

        //funcao para alterar visibilidade da senha


        document.getElementById('confirmar-ocultar').addEventListener('click', function () {
            const passwordField = document.getElementById('password_confirmation');
            const icon = this;

            // Alterna o tipo de input entre 'password' e 'text'
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash-fill" viewBox="0 0 16 16"><path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7 7 0 0 0 2.79-.588M5.21 3.088A7 7 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474z"/><path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12z"/></svg>`
            } else {
                passwordField.type = 'password';
                icon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16"><path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/><path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/></svg>`
            }
        });
    });

    //Alternar quando o icone aparece para mostrar a senha (CAMPO CONFIRMAR SENHA)
    const campoConfirmarSenha = document.getElementById('password_confirmation');
    const iconeOcultar2 = document.getElementById('confirmar-ocultar');
    campoConfirmarSenha.addEventListener('input', function () {
        if (campoConfirmarSenha.value.length > 0) {
            iconeOcultar2.style.display = 'block';
        } else {
            iconeOcultar2.style.display = 'none';
        }
    });

}

document.getElementById('ocultar').addEventListener('click', function () {
    const passwordField = document.getElementById('password');
    const icon = this;

    // Alterna o tipo de input entre 'password' e 'text'
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        icon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash-fill" viewBox="0 0 16 16"><path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7 7 0 0 0 2.79-.588M5.21 3.088A7 7 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474z"/><path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12z"/></svg>`
    } else {
        passwordField.type = 'password';
        icon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16"><path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/><path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/></svg>`
    }
});

//Alternar quando o icone aparece para mostrar a senha (CAMPO SENHA)
const campoSenha = document.getElementById('password');
const iconeOcultar = document.getElementById('ocultar');
campoSenha.addEventListener('input', function () {
    if (campoSenha.value.length > 0) {
        iconeOcultar.style.display = 'block';
    } else {
        iconeOcultar.style.display = 'none';
    }
});

//Validação para verificar se os requisitos do formulario foram atendidos
if (rotaAtual === "register") {
    const botao = document.getElementById('registrar');
    botao.addEventListener('click', (event) => {
        event.preventDefault(); // Prevenir o envio do formulário
        const nome = document.getElementById('name');
        const email = document.getElementById('email');
        const cpf_cnpj = document.getElementById('cpf_cnpj');
        const password = document.getElementById('password');
        const password_confirmation = document.getElementById('password_confirmation');

        // Validação de nome
        if (nome.value.trim().length < 3) {
            const mensagem = document.getElementById('erro-nome');
            nome.classList.add('invalido');
            mensagem.style.color = 'red';
            mensagem.textContent = 'Informe seu nome';
            nome.focus();
            camposValidos.nome = false;
        }
        // Validação de email
        if (email.value.length <= 0) {
            const mensagem = document.getElementById('erro-email');
            email.classList.add('invalido');
            mensagem.style.color = 'red';
            mensagem.textContent = 'Informe seu email';
            email.focus();
            camposValidos.email = false;
        }

        // Validação de CPF/CNPJ
        if (cpf_cnpj.value.length <= 0) {
            const mensagem = document.getElementById('erro-cpf-cnpj');
            cpf_cnpj.classList.add('invalido');
            mensagem.style.color = 'red';
            mensagem.textContent = 'Informe seu CPF ou CNPJ';
            cpf_cnpj.focus();
            camposValidos.cpfCnpj = false;
        }

        // Validação de senha
        if (password.value.length <= 0) {
            const mensagem = document.getElementById('erro-senha');
            password.classList.add('invalido');
            mensagem.style.color = 'red';
            mensagem.textContent = 'Informe uma senha e após isso confirme sua senha';
            password.focus();
            camposValidos.senha = false;
        }

        // Validação de confirmação de senha
        if (password_confirmation.value.length <= 0) {
            const mensagem = document.getElementById('erro-confirmar-senha');
            password_confirmation.classList.add('invalido');
            mensagem.style.color = 'red';
            mensagem.textContent = 'Informe uma senha e após isso confirme sua senha';
            password_confirmation.focus();
            camposValidos.confirmarSenha = false;
        }
        if(password_confirmation.value != password.value){
            const mensagem = document.getElementById('erro-confirmar-senha');
            password_confirmation.classList.add('invalido');
            mensagem.style.color = 'red';
            mensagem.textContent = 'As senhas não coincidem';
            password_confirmation.focus();
            camposValidos.confirmarSenha = false;
        }
        console.log(verificarFormulario());
        if (!verificarFormulario()) {
            Swal.fire({
                icon: "error",
                title: "Nem todos os requisitos do cadastro foram atendidos!",
                showClass: {
                    popup: 'animate__backInUp'
                },
                hideClass: {
                    popup: 'animate__fadeOutDown'
                }
            });
            return;
        } else if (verificarFormulario()) {
            document.getElementById('form').submit();
        }

    });
} else if (rotaAtual === 'login') {
    const botao = document.getElementById('login');
    botao.addEventListener('click', (event) => {
        event.preventDefault(); // Prevenir o envio do formulário
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        // Validação de email
        if (email.value.length <= 0) {
            const mensagem = document.getElementById('erro-email');
            email.classList.add('invalido');
            mensagem.style.color = 'red';
            mensagem.textContent = 'Informe seu email';
            email.focus();
            camposValidos.email = false;
        }

        // Validação de senha
        if (password.value.length <= 0) {
            const mensagem = document.getElementById('erro-senha');
            password.classList.add('invalido');
            mensagem.style.color = 'red';
            mensagem.textContent = 'Informe uma senha e após isso confirme sua senha';
            password.focus();
            camposValidos.senha = false;
        }


        console.log(verificarFormulario());
        if (!verificarFormulario()) {
            Swal.fire({
                icon: "error",
                title: "Nem todos os requisitos do cadastro foram atendidos!",
                showClass: {
                    popup: 'animate__backInUp'
                },
                hideClass: {
                    popup: 'animate__fadeOutDown'
                }
            });
            return;
        } else if (verificarFormulario()) {
            document.getElementById('form').submit();
        }

    });
}