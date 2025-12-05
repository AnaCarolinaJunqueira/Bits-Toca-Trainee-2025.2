const togglePassword = document.querySelector('#togglePassword');
const password = document.querySelector('.senha');

if (togglePassword && password) {
    togglePassword.addEventListener('click', () => {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        togglePassword.classList.toggle('fa-eye');
        togglePassword.classList.toggle('fa-eye-slash');
    });
}
