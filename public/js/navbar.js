document.addEventListener('DOMContentLoaded', () => {
    const menuHamburger = document.querySelector("#menu-icone");
    const menuItens = document.querySelector(".menu-itens");

    if (menuHamburger && menuItens) {
        menuHamburger.addEventListener("click", () => {
            menuItens.classList.toggle("active");
            menuHamburger.classList.toggle("bi-list");
            menuHamburger.classList.toggle("bi-x-square");
        });
    }

    const userBtn = document.getElementById('user-dropdown-btn');
    const userMenu = document.getElementById('user-dropdown-menu');
    const seta = document.querySelector('#user-dropdown-btn i');

    if (userBtn && userMenu) {
        userBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            userMenu.classList.toggle("show");
            seta.classList.toggle("bi-caret-down-fill");
            seta.classList.toggle("bi-caret-up-fill");
        });

        document.addEventListener('click', (e) => {
            if (!userBtn.contains(e.target) && !userMenu.contains(e.target)) {
                userMenu.classList.remove('show');
            }
        });
    }

    const btnEditProfile = document.getElementById('btn-edit-profile-nav');
    
    if (btnEditProfile) {
        btnEditProfile.addEventListener('click', (e) => {
            e.preventDefault();
            if(userMenu) userMenu.classList.remove('show');

            const data = btnEditProfile.dataset;

            const idInput = document.getElementById('edit-usuario-id');
            const nomeInput = document.getElementById('edit-nome');
            const emailInput = document.getElementById('edit-email');
            const senhaInput = document.getElementById('edit-senha');
            const adminCheckbox = document.getElementById('edit-is-admin');
            const photoDrop = document.getElementById('drop-edit-user-photo');

            if (idInput) idInput.value = data.id;
            if (nomeInput) nomeInput.value = data.nome;
            if (emailInput) emailInput.value = data.email;
            if (senhaInput) senhaInput.value = '';
            
            if (adminCheckbox) adminCheckbox.checked = data.is_admin == '1';

            if (photoDrop) {
                const oldPreview = photoDrop.querySelector('img.preview');
                if (oldPreview) oldPreview.remove();
                
                const placeholder = photoDrop.querySelector('.circle-placeholder');
                
                if (data.avatar && data.avatar !== 'assets/avatars/default.png' && data.avatar !== '') {
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
                    photoDrop.prepend(img);
                } else {
                    if (placeholder) placeholder.style.display = 'flex';
                }
            }

            if (typeof abrirModal === 'function') {
                abrirModal('modal-editar-usuario');
            } else {
                console.error('Função abrirModal não encontrada. Verifique se modals.js está importado.');
            }
        });
    }

    const logo = document.getElementById('navbar-logo');
    const videoModal = document.getElementById('video-modal');
    const videoElement = document.getElementById('video');
    const closeBtn = document.querySelector('.close-video-modal');

    let clickCount = 0;
    let clickTimeout;

    if (logo && videoModal && videoElement) {
        logo.addEventListener('click', (e) => {
            if (!document.querySelector('.hero-section')) return;

            e.preventDefault(); 
            
            clickCount++;

            clearTimeout(clickTimeout);
            clickTimeout = setTimeout(() => {
                clickCount = 0;
            }, 500);

            if (clickCount === 4) {
                videoModal.style.display = 'block';
                videoElement.play();
                clickCount = 0;
            }
        });

        const closeModal = () => {
            videoModal.style.display = 'none';
            videoElement.pause();
            videoElement.currentTime = 0;
        };

        videoElement.addEventListener('ended', closeModal);

        if (closeBtn) {
            closeBtn.addEventListener('click', closeModal);
        }

        window.addEventListener('click', (e) => {
            if (e.target === videoModal) {
                closeModal();
            }
        });
    }
});