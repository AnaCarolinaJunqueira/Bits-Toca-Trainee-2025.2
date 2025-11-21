const figurinha = document.querySelectorAll('.figurinha');

figurinha.forEach(fig => {
    fig.addEventListener('click', () => {
        fig.classList.toggle('active');
    });
});