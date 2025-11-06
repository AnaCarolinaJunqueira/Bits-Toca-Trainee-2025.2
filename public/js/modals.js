function abrirModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
    }
}

function fecharModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
    }
}

function initializeModalLogic() {
    
    const novoPostButton = document.querySelector('.botao-post');
    if (novoPostButton) {
        novoPostButton.addEventListener('click', () => {
            abrirModal('modal-novo');
        });
    }

    document.querySelectorAll('.file-drop-area').forEach(dropArea => {
        const fileInput = dropArea.querySelector('.file-input');
        const textElement = dropArea.querySelector('p');

        if (!fileInput || !textElement) {
            return;
        }

        fileInput.addEventListener('dragover', () => {
            dropArea.style.borderColor = '#DC97A5';
            dropArea.style.backgroundColor = '#fff';
        });

        fileInput.addEventListener('dragleave', () => {
            dropArea.style.borderColor = '#ccc';
            dropArea.style.backgroundColor = '#fafafa';
        });

        fileInput.addEventListener('drop', () => {
            dropArea.style.borderColor = '#ccc';
            dropArea.style.backgroundColor = '#fafafa';
        });
    });

    document.querySelectorAll('#modal-novo .star-rating, #modal-editar .star-rating').forEach(starGroup => {
        
        const stars = starGroup.querySelectorAll('.bi');
        const ratingInput = starGroup.querySelector('input[type="hidden"]');
        let currentRating = parseInt(ratingInput.value) || 0;

        function setRatingVisuals(value) {
            stars.forEach(star => {
                const starValue = parseInt(star.dataset.value);
                if (starValue <= value) {
                    star.classList.remove('bi-star');
                    star.classList.add('bi-star-fill');
                    star.style.color = '#f39c12';
                } else {
                    star.classList.remove('bi-star-fill');
                    star.classList.add('bi-star');
                    star.style.color = '#ccc';
                }
            });
        }

        stars.forEach(star => {
            star.addEventListener('click', () => {
                const newRating = parseInt(star.dataset.value);
                
                if (newRating === currentRating) {
                    currentRating = 0;
                } else {
                    currentRating = newRating;
                }
                
                ratingInput.value = currentRating;
                setRatingVisuals(currentRating);
            });

            star.addEventListener('mouseover', () => {
                const hoverValue = parseInt(star.dataset.value);
                setRatingVisuals(hoverValue);
            });
        });

        starGroup.addEventListener('mouseleave', () => {
            setRatingVisuals(currentRating);
        });

        setRatingVisuals(currentRating);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initializeModalLogic();
});