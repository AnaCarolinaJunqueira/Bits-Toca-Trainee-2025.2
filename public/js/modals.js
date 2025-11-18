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

        if (modalId === 'modal-novo' || modalId === 'modal-editar') {
            const dropArea = modal.querySelector('.file-drop-area');
            if (dropArea) {
                const oldPreview = dropArea.querySelector('img.preview');
                if (oldPreview) {
                    oldPreview.remove();
                }
                const icon = dropArea.querySelector('i');
                const text = dropArea.querySelector('p');
                if (icon) icon.style.display = 'block';
                if (text) text.style.display = 'block';
                const fileInput = dropArea.querySelector('input[type="file"]');
                if (fileInput) fileInput.value = null;
            }
        }

        if (modalId === 'modal-visualizar') {
            const viewImageContainer = modal.querySelector('.file-drop-area');
            if (viewImageContainer) {
                viewImageContainer.innerHTML = '<i class="bi bi-image-fill" style="font-size: 3rem; color: #ccc;"></i>';
            }
        }
    }
}

function setStarRating(starGroup, ratingValue) {
    const stars = starGroup.querySelectorAll('.bi');
    const ratingInput = starGroup.querySelector('input[type="hidden"]');

    if (ratingInput) {
        ratingInput.value = ratingValue;
    }

    stars.forEach(star => {
        const starValue = parseInt(star.dataset.value);
        if (starValue <= ratingValue) {
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

function setCustomSelectValue(originalSelect, newValue) {
    if (!originalSelect) return;

    originalSelect.value = newValue;

    const customSelectWrapper = originalSelect.closest('.custom-select');
    if (!customSelectWrapper) return;

    const styledSelect = customSelectWrapper.querySelector('.select-styled');
    const optionsList = customSelectWrapper.querySelector('.select-options');

    if (styledSelect) {
        const selectedOption = originalSelect.options[originalSelect.selectedIndex];
        styledSelect.textContent = selectedOption ? selectedOption.textContent : 'Selecione uma categoria';
    }

    if (optionsList) {
        optionsList.querySelectorAll('div').forEach(div => div.classList.remove('is-selected'));
        const selectedOptionDiv = optionsList.querySelector(`div[data-value="${newValue}"]`);
        if (selectedOptionDiv) {
            selectedOptionDiv.classList.add('is-selected');
        }
    }
}

function setupImagePreview(modalSelector) {
    const modal = document.querySelector(modalSelector);
    if (!modal) return;

    const fileInput = modal.querySelector('input[type="file"][name="imagem"]');
    const dropArea = modal.querySelector('.file-drop-area');

    if (!fileInput || !dropArea) return;

    const icon = dropArea.querySelector('i');
    const text = dropArea.querySelector('p');

    fileInput.addEventListener('change', event => {
        const file = event.target.files[0];

        const oldPreview = dropArea.querySelector('img.preview');
        if (oldPreview) oldPreview.remove();

        if (!file) {
            if (icon) icon.style.display = 'block';
            if (text) text.style.display = 'block';
            return;
        }

        const reader = new FileReader();
        reader.onload = e => {
            if (icon) icon.style.display = 'none';
            if (text) text.style.display = 'none';

            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'preview';
            img.style = "max-width: 100%; max-height: 200px; object-fit: contain; border-radius: 0.5rem; margin-bottom: 1rem;";
            dropArea.prepend(img);
        };
        reader.readAsDataURL(file);
    });
}
function initializeModalLogic() {

    const novoPostButton = document.querySelector('.botao-post');
    if (novoPostButton) {
        novoPostButton.addEventListener('click', () => {
            document.getElementById('novo-autor').value = 'Admin (ID 1)';
            document.getElementById('novo-data').value = new Date().toISOString().split('T')[0];
            setStarRating(document.querySelector('#modal-novo .star-rating'), 0);
            abrirModal('modal-novo');
        });
    }

    document.body.addEventListener('click', event => {
        const viewButton = event.target.closest('.btn-view');
        if (viewButton) {
            const data = viewButton.dataset;

            document.getElementById('view-titulo').value = data.titulo;
            document.getElementById('view-descricao').value = data.descricao;
            document.getElementById('view-autor').value = data.autor_nome;
            document.getElementById('view-data').value = data.data;

            document.getElementById('view-categoria').value = data.categoria;
            document.getElementById('view-data-edicao').textContent = data.data_edicao ? `Última edição: ${data.data_edicao}` : '';

            const viewImageContainer = document.querySelector('#modal-visualizar .file-drop-area');
            if (data.imagem) {
                viewImageContainer.innerHTML = `<img src="/public/${data.imagem}" alt="Imagem do Post" style="max-width: 100%; max-height: 300px; object-fit: contain; border-radius: 0.5rem;">`;
            } else {
                viewImageContainer.innerHTML = '<i class="bi bi-image-fill" style="font-size: 3rem; color: #ccc;"></i>';
            }

            const viewStars = document.querySelector('#modal-visualizar .star-rating');
            viewStars.querySelectorAll('.bi').forEach((star, index) => {
                if (index < parseInt(data.rating)) {
                    star.classList.add('bi-star-fill');
                    star.classList.remove('bi-star');
                } else {
                    star.classList.add('bi-star');
                    star.classList.remove('bi-star-fill');
                }
            });

            abrirModal('modal-visualizar');
        }
    });

    document.body.addEventListener('click', event => {
        const editButton = event.target.closest('.btn-edit');
        if (editButton) {
            const data = editButton.dataset;

            document.getElementById('edit-post-id').value = data.id;
            document.getElementById('edit-titulo').value = data.titulo;
            document.getElementById('edit-descricao').value = data.descricao;
            document.getElementById('edit-autor').value = data.autor_nome;
            document.getElementById('edit-data').value = data.data;
            document.getElementById('edit-data-edicao').textContent = data.data_edicao ? `Última edição: ${data.data_edicao}` : '';

            const editImageContainer = document.querySelector('#modal-editar .file-drop-area');
            const icon = editImageContainer.querySelector('i');
            const text = editImageContainer.querySelector('p');

            const oldPreview = editImageContainer.querySelector('img.preview');
            if (oldPreview) {
                oldPreview.remove();
            }

            if(data.imagem) {
                const img = document.createElement('img');
                img.src = `/public/${data.imagem}`;
                img.className = 'preview';
                img.style = "max-width: 100%; max-height: 300px; object-fit: contain; border-radius: 0.5rem;";
                editImageContainer.prepend(img);

                if (icon) icon.style.display = 'none';
                if (text) text.style.display = 'none';
            } else {
                if (icon) icon.style.display = ''; 
                if (text) text.style.display = ''; 
            }
            
            const editStars = document.querySelector('#modal-editar .star-rating');
            setStarRating(editStars, parseInt(data.rating));

            const editSelect = document.getElementById('edit-categoria-select');
            setCustomSelectValue(editSelect, data.categoria);

            abrirModal('modal-editar');
        }
    });

    document.body.addEventListener('click', event => {
        const deleteButton = event.target.closest('.btn-delete');
        if (deleteButton) {
            const postId = deleteButton.dataset.id;
            document.getElementById('delete-post-id').value = postId;
            abrirModal('modal-delete');
        }
    });

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

        function updateHoverVisuals(hoverValue) {
            stars.forEach(star => {
                const starValue = parseInt(star.dataset.value);
                if (starValue <= hoverValue) {
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
                const currentSavedRating = parseInt(ratingInput.value) || 0;

                const finalRating = (newRating === currentSavedRating) ? 0 : newRating;

                setStarRating(starGroup, finalRating);
            });

            star.addEventListener('mouseover', () => {
                const hoverValue = parseInt(star.dataset.value);
                updateHoverVisuals(hoverValue);
            });
        });

        starGroup.addEventListener('mouseleave', () => {
            const currentSavedRating = parseInt(ratingInput.value) || 0;
            setStarRating(starGroup, currentSavedRating);
        });
    });
    const gotoBtns = document.querySelectorAll('.open-goto-modal');
    gotoBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const totalPages = btn.dataset.totalPages;
            const modal = document.getElementById('modal-goto-page');
            const input = document.getElementById('goto-page-input');
            const label = document.getElementById('goto-page-label');
            const errorEl = document.getElementById('goto-page-error');

            modal.dataset.totalPages = totalPages;
            input.max = totalPages;
            label.textContent = `Digite uma página (1 - ${totalPages})`;

            input.value = '';
            errorEl.style.display = 'none';
            
            abrirModal('modal-goto-page');

            setTimeout(() => input.focus(), 100);
        });
    });

    const confirmGotoBtn = document.getElementById('btn-confirm-goto');
    if (confirmGotoBtn) {
        confirmGotoBtn.addEventListener('click', () => {
            const modal = document.getElementById('modal-goto-page');
            const input = document.getElementById('goto-page-input');
            const errorEl = document.getElementById('goto-page-error');
            
            const pageNumber = parseInt(input.value);
            const totalPages = parseInt(modal.dataset.totalPages);

            if (isNaN(pageNumber) || pageNumber < 1 || pageNumber > totalPages) {
                errorEl.textContent = `Por favor, digite um número entre 1 e ${totalPages}.`;
                errorEl.style.display = 'block';
                input.focus();
            } else {
                errorEl.style.display = 'none';
                window.location.href = `?page=${pageNumber}`;
            }
        });

        const gotoInput = document.getElementById('goto-page-input');
        if (gotoInput) {
            gotoInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    confirmGotoBtn.click();
                }
            });
        }
    }

}

function initializeCustomSelects() {
    document.querySelectorAll('.custom-select').forEach(wrapper => {
        const select = wrapper.querySelector('select');
        if (!select) return;

        const styledSelect = document.createElement('div');
        styledSelect.className = 'select-styled';
        styledSelect.textContent = select.options[select.selectedIndex].textContent;
        wrapper.appendChild(styledSelect);

        const optionsList = document.createElement('div');
        optionsList.className = 'select-options';

        Array.from(select.options).forEach((option, index) => {
            if (option.disabled) return;

            const optionDiv = document.createElement('div');
            optionDiv.textContent = option.textContent;
            optionDiv.dataset.value = option.value;

            if (index === select.selectedIndex) {
                optionDiv.classList.add('is-selected');
            }

            optionDiv.addEventListener('click', () => {
                styledSelect.textContent = option.textContent;

                select.value = option.value;

                optionsList.querySelectorAll('div').forEach(div => div.classList.remove('is-selected'));
                optionDiv.classList.add('is-selected');

                styledSelect.classList.remove('active');
                optionsList.classList.remove('active');
            });

            optionsList.appendChild(optionDiv);
        });

        wrapper.appendChild(optionsList);

        styledSelect.addEventListener('click', (e) => {
            e.stopPropagation();
            closeAllSelects(styledSelect);
            styledSelect.classList.toggle('active');
            optionsList.classList.toggle('active');
        });
    });
}

function closeAllSelects(exceptThisOne) {
    document.querySelectorAll('.select-styled').forEach(styled => {
        if (styled !== exceptThisOne) {
            styled.classList.remove('active');
            styled.nextElementSibling.classList.remove('active');
        }
    });
}

document.addEventListener('click', () => {
    closeAllSelects(null);
});

document.addEventListener('DOMContentLoaded', () => {
    initializeModalLogic();
    initializeCustomSelects();

    setupImagePreview('#modal-novo');
    setupImagePreview('#modal-editar');
});