document.getElementById('email').addEventListener('input', function () {
    const email = this;
    const emailDigitado = email.value;
    const mensagem = document.getElementById('mensagem');

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

    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}