const togglePassword = document.querySelector('#togglePassword')
const password = document.querySelector('#check-password')
const botaologin = document.querySelector('.botao-loginpage')

togglePassword.addEventListener('click', () => {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    togglePassword.classList.toggle('fa-eye');
    togglePassword.classList.toggle('fa-eye-slash');
});
botaologin.addEventListener('click', () => {
window.location.href = '/index.html';    
});

