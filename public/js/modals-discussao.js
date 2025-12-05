
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

        const dropAreas = modal.querySelectorAll('.file-drop-area');
        dropAreas.forEach(dropArea => {
            const oldPreview = dropArea.querySelector('img.preview');
            if (oldPreview) oldPreview.remove();
            
            const icon = dropArea.querySelector('i');
            const text = dropArea.querySelector('p');
            if (icon) icon.style.display = 'block';
            if (text) text.style.display = 'block';
            
            const fileInput = dropArea.querySelector('input[type="file"]');
            if (fileInput) fileInput.value = null;
        });
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

    const textInputs = form.querySelectorAll('input[type="text"]:not([disabled]), textarea:not([disabled])');
    textInputs.forEach(input => {
        if (input.hasAttribute('required') && !input.value.trim()) {
            showError(input, 'Este campo é obrigatório.');
            isValid = false;
        } else {
            removeError(input);
        }
    });

    const select = form.querySelector('select:not([disabled])');
    if (select && select.hasAttribute('required') && !select.value) {
        isValid = false;
    }

    if (!isValid) {
        event.preventDefault();
    }
}

function setCustomSelectValue(originalSelect, newValue) {
    if (!originalSelect) return;
    originalSelect.value = newValue;
    const customSelectWrapper = originalSelect.closest('.custom-select');
    if (!customSelectWrapper) return;
    const styledSelect = customSelectWrapper.querySelector('.select-styled');
    
    if (styledSelect) {
        const selectedOption = Array.from(originalSelect.options).find(opt => opt.value === newValue);
        styledSelect.textContent = selectedOption ? selectedOption.textContent : 'Selecione uma categoria';
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

            const oldPreview = dropArea.querySelector('img.preview');
            if (oldPreview) oldPreview.remove();

            if (!file) {
                if (icon) icon.style.display = 'block';
                if (text) text.style.display = 'block';
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

                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'preview';
                img.style.cssText = "max-width: 100%; max-height: 200px; object-fit: contain; border-radius: 0.5rem; margin-bottom: 1rem;";
                
                dropArea.prepend(img);
            };
            reader.readAsDataURL(file);
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const formNovaDiscussao = document.getElementById('form-nova-discussao');
    if (formNovaDiscussao) formNovaDiscussao.addEventListener('submit', validateForm);

    const formEditDiscussao = document.getElementById('form-editar-discussao');
    if (formEditDiscussao) formEditDiscussao.addEventListener('submit', validateForm);

    setupImagePreviews();
    
    initializeCustomSelects();

    document.body.addEventListener('click', event => {
        const editButton = event.target.closest('.btn-edit-discussion');
        if (editButton) {
            const data = editButton.dataset;
            
            document.getElementById('edit-discussao-id').value = data.id;
            document.getElementById('edit-discussao-titulo').value = data.titulo;
            document.getElementById('edit-discussao-conteudo').value = data.conteudo;
            
            const editSelect = document.getElementById('edit-discussao-categoria');
            setCustomSelectValue(editSelect, data.categoria);

            const area = document.getElementById('drop-edit-discussao-imagem');
            const imgSrc = data.imagem;

            const oldP = area.querySelector('img.preview');
            if (oldP) oldP.remove();
            const icon = area.querySelector('i');
            const p = area.querySelector('p');
            const fileInput = area.querySelector('input[type="file"]');
            if(fileInput) fileInput.value = null;

            if (imgSrc) {
                const img = document.createElement('img');
                img.src = `/public/${imgSrc}`;
                img.className = 'preview';
                img.style.cssText = "max-width: 100%; max-height: 200px; object-fit: contain; border-radius: 0.5rem; margin-bottom: 1rem;";
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

    const gotoBtns = document.querySelectorAll('.open-goto-modal');
    gotoBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const totalPages = btn.dataset.totalPages;
            const modal = document.getElementById('modal-goto-page');
            const input = document.getElementById('goto-page-input');
            const label = document.getElementById('goto-page-label');
            const errorEl = document.getElementById('goto-page-error');

            modal.dataset.totalPages = totalPages;
            if(input) {
                input.max = totalPages;
                input.value = '';
            }
            if(label) label.textContent = `Digite uma página (1 - ${totalPages})`;
            if(errorEl) errorEl.style.display = 'none';
            
            abrirModal('modal-goto-page');
            setTimeout(() => { if(input) input.focus() }, 100);
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
                if(errorEl) {
                    errorEl.textContent = `Por favor, digite um número entre 1 e ${totalPages}.`;
                    errorEl.style.display = 'block';
                }
                input.focus();
            } else {
                if(errorEl) errorEl.style.display = 'none';
                const currentSearchParams = new URLSearchParams(window.location.search);
                currentSearchParams.set('page', pageNumber);
                const baseUrl = window.location.pathname;
                window.location.href = `${baseUrl}?${currentSearchParams.toString()}`;
            }
        });
    }

    document.body.addEventListener('click', event => {
        const deleteButton = event.target.closest('.btn-delete-reply-modal');
        if (deleteButton) {
            const replyId = deleteButton.dataset.id;
            
            const inputId = document.getElementById('delete-reply-id'); 
            
            if (inputId) inputId.value = replyId;
            
            abrirModal('modal-delete-reply');
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
});

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