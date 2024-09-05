/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	// The require scope
/******/ 	var __webpack_require__ = {};
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
/*!*******************************!*\
  !*** ./resources/js/login.js ***!
  \*******************************/
__webpack_require__.r(__webpack_exports__);
// VALIDACOES EMAIL
document.getElementById('email').addEventListener('keyup', function () {
  var email = this;
  var emailDigitado = email.value;
  var mensagem = document.getElementById('erro-email');
  this.classList.remove('valido', 'invalido');
  mensagem.textContent = '';
  if (validarEmailFormato(emailDigitado)) {
    email.classList.remove('invalido');
    email.classList.add('valido');
    mensagem.textContent = '';
  } else {
    email.classList.remove('valido');
    email.classList.add('invalido');
    mensagem.style.color = 'red';
    mensagem.textContent = 'Email inválido';
  }
});
function validarEmailFormato(email) {
  var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return regex.test(email);
}

// Formatação do CPF e CNPJ
document.getElementById('cpf_cnpj').addEventListener('keyup', function () {
  var cpf_cnpj = this.value.replace(/[^\d]+/g, ''); // Remove tudo que não for dígito

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
document.getElementById('cpf_cnpj').addEventListener('blur', function () {
  var mensagem = document.getElementById('erro-cpf-cnpj');
  this.classList.remove('valido', 'invalido');
  mensagem.textContent = '';
  var cpf_cnpj = this.value.replace(/[^\d]+/g, ''); // Remove tudo que não for dígito

  if (cpf_cnpj.length === 11) {
    // Validação do CPF
    if (/^(\d)\1{10}$/.test(cpf_cnpj)) {
      this.classList.remove('valido');
      this.classList.add('invalido');
      mensagem.style.color = 'red';
      mensagem.textContent = 'CPF inválido';
      return;
    }
    var soma = 0;
    for (var i = 0; i < 9; i++) {
      soma += parseInt(cpf_cnpj.charAt(i)) * (10 - i);
    }
    var primeiroDigito = 11 - soma % 11;
    if (primeiroDigito === 10 || primeiroDigito === 11) {
      primeiroDigito = 0;
    }
    if (primeiroDigito !== parseInt(cpf_cnpj.charAt(9))) {
      this.classList.remove('valido');
      this.classList.add('invalido');
      mensagem.style.color = 'red';
      mensagem.textContent = 'CPF inválido';
      return;
    }
    soma = 0;
    for (var _i = 0; _i < 10; _i++) {
      soma += parseInt(cpf_cnpj.charAt(_i)) * (11 - _i);
    }
    var segundoDigito = 11 - soma % 11;
    if (segundoDigito === 10 || segundoDigito === 11) {
      segundoDigito = 0;
    }
    if (segundoDigito !== parseInt(cpf_cnpj.charAt(10))) {
      this.classList.remove('valido');
      this.classList.add('invalido');
      mensagem.style.color = 'red';
      mensagem.textContent = 'CPF inválido';
      return;
    }
    this.classList.remove('invalido');
    this.classList.add('valido');
    mensagem.textContent = '';
  } else if (cpf_cnpj.length === 14) {
    // Validação do CNPJ
    if (/^(\d)\1{13}$/.test(cpf_cnpj)) {
      this.classList.remove('valido');
      this.classList.add('invalido');
      mensagem.style.color = 'red';
      mensagem.textContent = 'CNPJ inválido';
      return;
    }
    var tamanho = cpf_cnpj.length - 2;
    var numeros = cpf_cnpj.substring(0, tamanho);
    var digitos = cpf_cnpj.substring(tamanho);
    var _soma = 0;
    var pos = tamanho - 7;
    for (var _i2 = tamanho; _i2 >= 1; _i2--) {
      _soma += numeros.charAt(tamanho - _i2) * pos--;
      if (pos < 2) {
        pos = 9;
      }
    }
    var resultado = _soma % 11 < 2 ? 0 : 11 - _soma % 11;
    if (resultado !== parseInt(digitos.charAt(0))) {
      this.classList.remove('valido');
      this.classList.add('invalido');
      mensagem.style.color = 'red';
      mensagem.textContent = 'CNPJ inválido';
      return;
    }
    tamanho = tamanho + 1;
    numeros = cpf_cnpj.substring(0, tamanho);
    _soma = 0;
    pos = tamanho - 7;
    for (var _i3 = tamanho; _i3 >= 1; _i3--) {
      _soma += numeros.charAt(tamanho - _i3) * pos--;
      if (pos < 2) {
        pos = 9;
      }
    }
    resultado = _soma % 11 < 2 ? 0 : 11 - _soma % 11;
    if (resultado !== parseInt(digitos.charAt(1))) {
      this.classList.remove('valido');
      this.classList.add('invalido');
      mensagem.style.color = 'red';
      mensagem.textContent = 'CNPJ inválido';
      return;
    }
    this.classList.remove('invalido');
    this.classList.add('valido');
    mensagem.textContent = '';
  } else {
    this.classList.remove('valido');
    this.classList.add('invalido');
    mensagem.style.color = 'red';
    mensagem.textContent = 'Número inválido';
  }
});

// VALIDACOES SENHA
document.getElementById('password').addEventListener('keyup', function () {
  var mensagem = document.getElementById('erro-senha');
  this.classList.remove('valido', 'invalido');
  mensagem.textContent = '';
  var password = this.value;
  if (password.length < 8) {
    this.classList.remove('valido');
    this.classList.add('invalido');
    mensagem.style.color = 'red';
    mensagem.textContent = 'Senha muito curta, informe uma senha com pelo menos 8 digitos';
    return;
  }
  if (!/[A-Z]/.test(password)) {
    this.classList.remove('valido');
    this.classList.add('invalido');
    mensagem.style.color = 'red';
    mensagem.textContent = 'Informe pelo menos uma letra maiuscula';
    return;
  }
  if (!/[a-z]/.test(password)) {
    this.classList.remove('valido');
    this.classList.add('invalido');
    mensagem.style.color = 'red';
    mensagem.textContent = 'Informe pelo menos uma letra minuscula';
    return;
  }

  // Senha valida
  this.classList.remove('invalido');
  this.classList.add('valido');
  mensagem.textContent = '';
});
document.getElementById('password_confirmation').addEventListener('blur', function () {
  var mensagem = document.getElementById('erro-confirmar-senha');
  var confirmarSenha = this.value;
  var senha = document.getElementById('password').value;
  if (senha !== confirmarSenha) {
    this.classList.remove('valido');
    this.classList.add('invalido');
    mensagem.style.color = 'red';
    mensagem.textContent = 'As senhas não coincidem';
    return;
  }
  this.classList.remove('invalido');
  this.classList.add('valido');
  mensagem.textContent = '';
});

//funcao para alterar visibilidade da senha

document.getElementById('ocultar').addEventListener('click', function () {
  var passwordField = document.getElementById('password');
  var icon = this;

  // Alterna o tipo de input entre 'password' e 'text'
  if (passwordField.type === 'password') {
    passwordField.type = 'text';
    icon.innerHTML = "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" class=\"bi bi-eye-slash-fill\" viewBox=\"0 0 16 16\"><path d=\"m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7 7 0 0 0 2.79-.588M5.21 3.088A7 7 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474z\"/><path d=\"M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12z\"/></svg>";
  } else {
    passwordField.type = 'password';
    icon.innerHTML = "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" class=\"bi bi-eye-fill\" viewBox=\"0 0 16 16\"><path d=\"M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0\"/><path d=\"M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7\"/></svg>";
  }
});
document.getElementById('confirmar-ocultar').addEventListener('click', function () {
  var passwordField = document.getElementById('password_confirmation');
  var icon = this;

  // Alterna o tipo de input entre 'password' e 'text'
  if (passwordField.type === 'password') {
    passwordField.type = 'text';
    icon.innerHTML = "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" class=\"bi bi-eye-slash-fill\" viewBox=\"0 0 16 16\"><path d=\"m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7 7 0 0 0 2.79-.588M5.21 3.088A7 7 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474z\"/><path d=\"M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12z\"/></svg>";
  } else {
    passwordField.type = 'password';
    icon.innerHTML = "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" class=\"bi bi-eye-fill\" viewBox=\"0 0 16 16\"><path d=\"M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0\"/><path d=\"M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7\"/></svg>";
  }
});
/******/ })()
;