const likes = document.querySelector('.likes-content');

likes.addEventListener('click', () => {
    likes.classList.toggle('active');
    document.querySelector('.likes-icon i').classList.toggle('bi-heart-fill');
    document.querySelector('.likes-icon i').classList.toggle('bi-heart');
});