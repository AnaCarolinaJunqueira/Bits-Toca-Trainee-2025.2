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
        const form = modal.querySelector('form');
        if (form) {
            resetValidation(form);
            form.reset();
        }

        if (modalId.includes('novo') || modalId.includes('editar')) {
            const dropAreas = modal.querySelectorAll('.file-drop-area');
            dropAreas.forEach(dropArea => {
                const oldPreview = dropArea.querySelector('img.preview');
                if (oldPreview) oldPreview.remove();
                
                const icon = dropArea.querySelector('i');
                const text = dropArea.querySelector('p');
                if (icon) icon.style.display = 'block';
                if (text) text.style.display = 'block';
                
                const placeholder = dropArea.querySelector('.circle-placeholder');
                if (placeholder) placeholder.style.display = 'flex';

                const fileInput = dropArea.querySelector('input[type="file"]');
                if (fileInput) fileInput.value = null;
            });
        }
    }
}

function showError(inputElement, message) {
    const formGroup = inputElement.closest('.form-group');
    if (!formGroup) return;
    const errorSpan = formGroup.querySelector('.error-message');
    if (errorSpan) {
        errorSpan.innerText = message || "Campo inválido";
        errorSpan.classList.add('active');
    }
    inputElement.classList.add('input-error');
}

function removeError(inputElement) {
    const formGroup = inputElement.closest('.form-group');
    if (!formGroup) return;
    const errorSpan = formGroup.querySelector('.error-message');
    if (errorSpan) {
        errorSpan.classList.remove('active');
    }
    inputElement.classList.remove('input-error');
}

function resetValidation(form) {
    const inputs = form.querySelectorAll('input, textarea, select');
    inputs.forEach(input => removeError(input));
}

function validateForm(event) {
    const form = event.target;
    let isValid = true;

    const textInputs = form.querySelectorAll('input[type="text"]:not([disabled]), textarea:not([disabled]), input[type="email"]:not([disabled]), input[type="password"]:not([disabled])');
    textInputs.forEach(input => {
        if (input.name === 'senha' && form.id === 'form-editar-usuario' && !input.value.trim()) {
            return;
        }
        
        if (input.hasAttribute('required') && !input.value.trim()) {
            showError(input, 'Este campo é obrigatório.');
            isValid = false;
        } else {
            removeError(input);
        }
    });

    const select = form.querySelector('select:not([disabled])');
    if (select && select.hasAttribute('required') && !select.value) {
        const wrapper = select.closest('.custom-select');
        const errorSpan = wrapper.parentElement.querySelector('.error-message');
        if (errorSpan) errorSpan.classList.add('active');
        if (wrapper) wrapper.querySelector('.select-styled').classList.add('input-error');
        isValid = false;
    } else if (select) {
        const wrapper = select.closest('.custom-select');
        const errorSpan = wrapper.parentElement.querySelector('.error-message');
        if (errorSpan) errorSpan.classList.remove('active');
        if (wrapper) wrapper.querySelector('.select-styled').classList.remove('input-error');
    }

    if (!isValid) {
        event.preventDefault();
    }
}


function setStarRating(starGroup, ratingValue) {
    const stars = starGroup.querySelectorAll('.bi');
    const ratingInput = starGroup.querySelector('input[type="hidden"]');
    if (ratingInput) ratingInput.value = ratingValue;

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
        const selectedOption = Array.from(originalSelect.options).find(opt => opt.value === newValue);
        styledSelect.textContent = selectedOption ? selectedOption.textContent : 'Selecione uma categoria';
    }
    if (optionsList) {
        optionsList.querySelectorAll('div').forEach(div => div.classList.remove('is-selected'));
        const selectedOptionDiv = optionsList.querySelector(`div[data-value="${newValue}"]`);
        if (selectedOptionDiv) selectedOptionDiv.classList.add('is-selected');
    }
}

function setupImagePreviews() {
    const fileInputs = document.querySelectorAll('.file-input');

    fileInputs.forEach(fileInput => {
        fileInput.addEventListener('change', event => {
            const dropArea = fileInput.closest('.file-drop-area');
            const file = event.target.files[0];
            const icon = dropArea.querySelector('i');
            const text = dropArea.querySelector('p');
            const placeholder = dropArea.querySelector('.circle-placeholder');


            const oldPreview = dropArea.querySelector('img.preview');
            if (oldPreview) oldPreview.remove();

            if (!file) {
                if (icon) icon.style.display = 'block';
                if (text) text.style.display = 'block';
                if (placeholder) placeholder.style.display = 'flex';
                return;
            }

            if (!file.type.startsWith('image/')) {
                alert("Por favor, selecione um arquivo de imagem.");
                fileInput.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = e => {
                if (icon) icon.style.display = 'none';
                if (text) text.style.display = 'none';
                if (placeholder) placeholder.style.display = 'none';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'preview';
                
                if (dropArea.classList.contains('image-upload-circle')) {
                    img.style.cssText = "width: 100%; height: 100%; object-fit: cover; border-radius: 50%; position: absolute; top: 0; left: 0;";
                } else {
                    img.style.cssText = "max-width: 100%; max-height: 200px; object-fit: contain; border-radius: 0.5rem; margin-bottom: 1rem;";
                }
                
                dropArea.prepend(img);
            };
            reader.readAsDataURL(file);
        });
    });
}

function helperLoadImageIntoArea(areaId, imagePath) {
    const area = document.getElementById(areaId);
    if (!area) return;

    area.innerHTML = '';

    if (imagePath && imagePath !== 'assets/images/default.png') {
        area.innerHTML = `<img src="/public/${imagePath}" class="preview" style="max-width: 100%; max-height: 300px;">`;
    } else {
        area.innerHTML = '<i class="bi bi-image-fill" style="font-size: 3rem; color: #ccc;"></i><p>Sem imagem</p>';
        const inputName = areaId.includes('featured') ? 'imagem_featured' : 'imagem_recent';
        if (!areaId.includes('view')) {
            area.innerHTML = `<i class="bi bi-cloud-arrow-up-fill"></i><p>Alterar img</p><input type="file" name="${inputName}" class="file-input" accept="image/*">`;
            setupImagePreviews();
        }
    }
}


function initializeModalLogic() {

    const formNovo = document.getElementById('form-novo-post');
    if (formNovo) formNovo.addEventListener('submit', validateForm);

    const formEdit = document.getElementById('form-editar-post');
    if (formEdit) formEdit.addEventListener('submit', validateForm);
    
    const formNovaDiscussao = document.getElementById('form-nova-discussao');
    if (formNovaDiscussao) formNovaDiscussao.addEventListener('submit', validateForm);

    const formEditDiscussao = document.getElementById('form-editar-discussao');
    if (formEditDiscussao) formEditDiscussao.addEventListener('submit', validateForm);


    const novoPostButton = document.querySelector('.botao-post');
    if (novoPostButton) {
        novoPostButton.addEventListener('click', () => {
            document.getElementById('novo-autor').value = 'Admin';
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
            document.getElementById('view-conteudo').value = data.conteudo;
            document.getElementById('view-autor').value = data.autor_nome;
            document.getElementById('view-data').value = data.data;
            document.getElementById('view-categoria').value = data.categoria;
            document.getElementById('view-data-edicao').textContent = data.data_edicao ? `Última edição: ${data.data_edicao}` : '';

            const areaFeatured = document.getElementById('view-img-featured-area');
            const areaRecent = document.getElementById('view-img-recent-area');

            areaFeatured.innerHTML = data.imagem ? `<img src="/public/${data.imagem}" style="max-width: 100%; max-height: 200px;">` : 'Sem imagem';
            areaRecent.innerHTML = data.imagem_recent ? `<img src="/public/${data.imagem_recent}" style="max-width: 100%; max-height: 200px;">` : 'Sem imagem';

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
            document.getElementById('edit-conteudo').value = data.conteudo;
            document.getElementById('edit-autor').value = data.autor_nome;
            document.getElementById('edit-data').value = data.data;
            document.getElementById('edit-data-edicao').textContent = data.data_edicao ? `Última edição: ${data.data_edicao}` : '';

            const populateEditPreview = (areaId, imgSrc, inputName) => {
                const area = document.getElementById(areaId);
                const oldP = area.querySelector('img.preview');
                if (oldP) oldP.remove();

                const icon = area.querySelector('i');
                const p = area.querySelector('p');

                if (imgSrc && imgSrc !== 'assets/images/default.png') {
                    const img = document.createElement('img');
                    img.src = `/public/${imgSrc}`;
                    img.className = 'preview';
                    img.style = "max-width: 100%; max-height: 200px; object-fit: contain; border-radius: 0.5rem; margin-bottom: 1rem;";
                    area.prepend(img);
                    if (icon) icon.style.display = 'none';
                    if (p) p.style.display = 'none';
                } else {
                    if (icon) icon.style.display = 'block';
                    if (p) p.style.display = 'block';
                }
            };

            populateEditPreview('drop-edit-featured', data.imagem, 'imagem_featured');
            populateEditPreview('drop-edit-recent', data.imagem_recent, 'imagem_recent');

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

    document.querySelectorAll('.star-rating').forEach(starGroup => {
        const stars = starGroup.querySelectorAll('.bi');
        const ratingInput = starGroup.querySelector('input[type="hidden"]');

        stars.forEach(star => {
            star.addEventListener('click', () => {
                if (starGroup.closest('#modal-visualizar')) return;
                const newRating = parseInt(star.dataset.value);
                setStarRating(starGroup, newRating);
            });
        });
    });

    document.body.addEventListener('click', event => {
        const editButton = event.target.closest('.btn-edit-discussion');
        if (editButton) {
            const data = editButton.dataset;
            const discussionId = data.id;

            document.getElementById('edit-discussao-id').value = discussionId;
            document.getElementById('edit-discussao-titulo').value = data.titulo;
            document.getElementById('edit-discussao-conteudo').value = data.conteudo;
            
            const editSelect = document.getElementById('edit-discussao-categoria');
            setCustomSelectValue(editSelect, data.categoria);

            const dropAreaId = 'drop-edit-discussao-imagem';
            const area = document.getElementById(dropAreaId);
            const imgSrc = data.imagem;

            const oldP = area.querySelector('img.preview');
            if (oldP) oldP.remove();

            const icon = area.querySelector('i');
            const p = area.querySelector('p');
            const fileInput = area.querySelector('input[type="file"]');
            if(fileInput) fileInput.value = null; // Clear file input value

            if (imgSrc) {
                const img = document.createElement('img');
                img.src = `/public/${imgSrc}`;
                img.className = 'preview';
                img.style = "max-width: 100%; max-height: 200px; object-fit: contain; border-radius: 0.5rem; margin-bottom: 1rem;";
                area.prepend(img);
                if (icon) icon.style.display = 'none';
                if (p) p.style.display = 'none';
            } else {
                if (icon) icon.style.display = 'block';
                if (p) p.style.display = 'block';
            }
            
            abrirModal('modal-editar-discussao');
        }
    });

    document.body.addEventListener('click', event => {
        const deleteButton = event.target.closest('.btn-delete-discussion-modal');
        if (deleteButton) {
            const discussionId = deleteButton.dataset.id;
            const inputId = document.getElementById('delete-discussion-id');
            if (inputId) inputId.value = discussionId;
            abrirModal('modal-delete-discussion');
        }
    });

    document.body.addEventListener('click', event => {
        const deleteButton = event.target.closest('.btn-delete-reply-modal');
        if (deleteButton) {
            const replyId = deleteButton.dataset.id;
            
            const inputId = document.getElementById('delete-reply-id');
            if (inputId) inputId.value = replyId;
            abrirModal('modal-delete-reply');
        }
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
                const currentSearchParams = new URLSearchParams(window.location.search);
                currentSearchParams.set('page', pageNumber);
                const baseUrl = window.location.pathname;
                window.location.href = `${baseUrl}?${currentSearchParams.toString()}`;
            }
        });
    }
}

function initializeCustomSelects() {
    document.querySelectorAll('.custom-select').forEach(wrapper => {
        const select = wrapper.querySelector('select');
        if (!select) return;

        let styledSelect = wrapper.querySelector('.select-styled');
        if (!styledSelect) {
            styledSelect = document.createElement('div');
            styledSelect.className = 'select-styled';
            wrapper.appendChild(styledSelect);
        }
        styledSelect.textContent = select.options[select.selectedIndex].textContent;

        let optionsList = wrapper.querySelector('.select-options');
        if (!optionsList) {
            optionsList = document.createElement('div');
            optionsList.className = 'select-options';
            wrapper.appendChild(optionsList);
        } else {
            optionsList.innerHTML = '';
        }

        Array.from(select.options).forEach((option, index) => {
            if (option.disabled) return;
            const optionDiv = document.createElement('div');
            optionDiv.textContent = option.textContent;
            optionDiv.dataset.value = option.value;
            if (index === select.selectedIndex) optionDiv.classList.add('is-selected');

            optionDiv.addEventListener('click', () => {
                styledSelect.textContent = option.textContent;
                select.value = option.value;

                const errorSpan = wrapper.parentElement.querySelector('.error-message');
                if (errorSpan) errorSpan.classList.remove('active');
                styledSelect.classList.remove('input-error');

                optionsList.querySelectorAll('div').forEach(div => div.classList.remove('is-selected'));
                optionDiv.classList.add('is-selected');
                styledSelect.classList.remove('active');
                optionsList.classList.remove('active');
            });
            optionsList.appendChild(optionDiv);
        });

        styledSelect.onclick = (e) => {
            e.stopPropagation();
            document.querySelectorAll('.select-styled').forEach(s => {
                if (s !== styledSelect) {
                    s.classList.remove('active');
                    if (s.nextElementSibling) s.nextElementSibling.classList.remove('active');
                }
            });
            styledSelect.classList.toggle('active');
            optionsList.classList.toggle('active');
        };
    });
}

document.addEventListener('click', () => {
    document.querySelectorAll('.select-styled').forEach(s => {
        s.classList.remove('active');
        if (s.nextElementSibling) s.nextElementSibling.classList.remove('active');
    });
});

document.addEventListener('DOMContentLoaded', () => {
    initializeModalLogic();
    initializeCustomSelects();
    setupImagePreviews();
});