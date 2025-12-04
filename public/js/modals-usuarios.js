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
            
            const placeholder = dropArea.querySelector('.circle-placeholder');
            if (placeholder) placeholder.style.display = 'flex';
            
            const fileInput = dropArea.querySelector('input[type="file"]');
            if (fileInput) fileInput.value = null;
        });
    }
}

function togglePasswordVisibility(inputId, iconElement) {
    const input = document.getElementById(inputId);
    if (input) {
        if (input.type === "password") {
            input.type = "text";
            iconElement.classList.remove('bi-eye-slash');
            iconElement.classList.add('bi-eye');
        } else {
            input.type = "password";
            iconElement.classList.remove('bi-eye');
            iconElement.classList.add('bi-eye-slash');
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

    const textInputs = form.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    textInputs.forEach(input => {
        if (form.id === 'form-editar-usuario' && input.name === 'senha' && !input.value) {
            return;
        }

        if (input.hasAttribute('required') && !input.value.trim()) {
            showError(input, 'Este campo é obrigatório.');
            isValid = false;
        } else if (input.type === 'email' && input.value.trim() && !emailPattern.test(input.value.trim())) {
            showError(input, 'Por favor, insira um email válido.');
            isValid = false;
        } else {
            removeError(input);
        }
    });

    if (!isValid) {
        event.preventDefault();
    }
}

function setupImagePreviews() {
    const fileInputs = document.querySelectorAll('.file-input');

    fileInputs.forEach(fileInput => {
        fileInput.addEventListener('change', event => {
            const dropArea = fileInput.closest('.file-drop-area');
            const file = event.target.files[0];
            const placeholder = dropArea.querySelector('.circle-placeholder');

            const oldPreview = dropArea.querySelector('img.preview');
            if (oldPreview) oldPreview.remove();

            if (!file) {
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
                if (placeholder) placeholder.style.display = 'none';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'preview';
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.objectFit = 'cover';
                img.style.borderRadius = '50%';
                img.style.position = 'absolute';
                img.style.top = '0';
                img.style.left = '0';
                
                dropArea.prepend(img);
            };
            reader.readAsDataURL(file);
        });
    });
}

function initializeModalLogic() {

    const formNovo = document.getElementById('form-novo-usuario');
    if (formNovo) formNovo.addEventListener('submit', validateForm);

    const formEdit = document.getElementById('form-editar-usuario');
    if (formEdit) formEdit.addEventListener('submit', validateForm);

    document.body.addEventListener('click', event => {
        const viewButton = event.target.closest('.btn-view-user');
        if (viewButton) {
            const data = viewButton.dataset;

            const nomeInput = document.getElementById('view-nome');
            const emailInput = document.getElementById('view-email');
            const adminCheckbox = document.getElementById('view-is-admin');
            
            if(nomeInput) nomeInput.value = data.nome;
            if(emailInput) emailInput.value = data.email;

            if (adminCheckbox) {
                adminCheckbox.checked = data.is_admin == '1';
            }

            const areaAvatar = document.getElementById('view-avatar-area');
            if (areaAvatar) {
                areaAvatar.innerHTML = data.avatar ? 
                    `<img src="/public/${data.avatar}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">` : 
                    '<div class="circle-placeholder"><p>Sem foto</p></div>';
            }

            abrirModal('modal-visualizar-usuario');
        }
    });

    document.body.addEventListener('click', event => {
        const editButton = event.target.closest('.btn-edit-user');
        if (editButton) {
            const data = editButton.dataset;

            document.getElementById('edit-usuario-id').value = data.id;
            document.getElementById('edit-nome').value = data.nome;
            document.getElementById('edit-email').value = data.email;
            
            document.getElementById('edit-senha').value = '';

            const adminCheckbox = document.getElementById('edit-is-admin');
            if (adminCheckbox) {
                adminCheckbox.checked = data.is_admin == '1';
            }

            const dropArea = document.getElementById('drop-edit-user-photo');
            const placeholder = dropArea.querySelector('.circle-placeholder');
            const oldPreview = dropArea.querySelector('img.preview');
            if (oldPreview) oldPreview.remove();

            if (data.avatar && data.avatar !== 'assets/images/default_user.png') {
                if (placeholder) placeholder.style.display = 'none';
                const img = document.createElement('img');
                img.src = `/public/${data.avatar}`;
                img.className = 'preview';
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.objectFit = 'cover';
                img.style.borderRadius = '50%';
                img.style.position = 'absolute';
                img.style.top = '0';
                img.style.left = '0';
                
                dropArea.prepend(img);
            } else {
                if (placeholder) placeholder.style.display = 'flex';
            }

            abrirModal('modal-editar-usuario');
        }
    });

    document.body.addEventListener('click', event => {
        const deleteButton = event.target.closest('.btn-delete-user');
        if (deleteButton) {
            const userId = deleteButton.dataset.id;
            const modal = document.getElementById('modal-deletar-usuario') || document.getElementById('modal-delete-user');
            const inputId = modal.querySelector('input[name="id"]');
            
            if (inputId) inputId.value = userId;
            
            if (modal) modal.classList.add('active');
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
                window.location.href = `?page=${pageNumber}`;
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    initializeModalLogic();
    setupImagePreviews();
});