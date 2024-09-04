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
document.getElementById('email').addEventListener('input', function () {
  var email = this;
  var emailDigitado = email.value;
  var mensagem = document.getElementById('mensagem');
  if (validarEmail(emailDigitado)) {
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
function validarEmail(email) {
  var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return regex.test(email);
}
/******/ })()
;