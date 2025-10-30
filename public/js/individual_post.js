const likes = document.querySelector('.likes-content');

likes.addEventListener('click', () => {
    likes.classList.toggle('active');
    // document.querySelector('.likes-text p').textContent = likes.classList.contains('active') ? '01' : '00';
    document.querySelector('.likes-icon i').classList.toggle('bi-heart-fill');
    document.querySelector('.likes-icon i').classList.toggle('bi-heart');
});