// Seleção de elementos do post em destaque
// Seleciona os elementos HTML necessários para o carrossel de posts em destaque.
const featuredContainer = document.querySelector('.featured-container');
const featuredSlides = document.querySelectorAll('.featured-slide');
const featuredDotsContainer = document.querySelector('.featured-dots');
const featuredPost = document.querySelector('.featured-post');

let featuredDots = []; // Array vazio

if (featuredDotsContainer && featuredSlides.length > 0) {
    featuredDotsContainer.innerHTML = ''; // Limpa labels existentes

    // Cria um ponto para cada card
    featuredSlides.forEach((slide, index) => {
        const dot = document.createElement('span');
        dot.classList.add('featured-dot');
        if (index === 0) {
            dot.classList.add('active'); // Seleciona o primeiro como ativo
        }
        featuredDotsContainer.appendChild(dot);
    });

    // Seleciona os labels criados
    featuredDots = document.querySelectorAll('.featured-dot');
}

let currentFeaturedIndex = 0; // Índice do slide atualmente exibido
let featuredAutoPlayInterval; // Intervalo para autoplay
const FEATURED_AUTO_PLAY_DELAY = 3000; // Delay entre as transições automáticas
const FEATURED_HOVER_CLASS = 'is-hovered'; // Classe CSS para hover
let isFeaturedHoverLocked = false; // Indica se o hover está travado (em dispositivos touch)
const isTouchPrimary = window.matchMedia('(hover: none)').matches || 'ontouchstart' in window; // Detecta se o dispositivo é touch


// Remove o efeito de hover de todos os slides em destaque e retira o estado ativo em dispositivos touch.
function clearFeaturedHover() {
    featuredSlides.forEach(slide => slide.classList.remove(FEATURED_HOVER_CLASS));

    // Se em um dispositivo touch, remova o estado ativo do post em destaque.
    if (isTouchPrimary && featuredPost) {
        featuredPost.classList.remove('is-touch-active');
    }
}


/**
 * 
 * @param {number} index - O índice do post que vai ser modificado.
 * @param {boolean} shouldHover - Verdadeiro para adicionar hover, Falso para remover.
 * @param {EventTarget} relatedTarget - O elemento que o mouse está se movendo para.
 */

function setFeaturedHover(index, shouldHover, relatedTarget) {
    featuredSlides.forEach((slide, slideIndex) => {

        // Se o slide não é o alvo, remova o hover.
        if (slideIndex !== index) {
            if (shouldHover) {

                // Verifica e garante que não há múltiplos hovers ativos.
                slide.classList.remove(FEATURED_HOVER_CLASS);

            }
            return;
        }

        // Se o mouse está se movendo para dentro do slide, ignore o evento.
        if (!shouldHover && relatedTarget && slide.contains(relatedTarget)) {
            return;
        }

        // Adiciona ou remove a classe de hover conforme necessário.
        slide.classList.toggle(FEATURED_HOVER_CLASS, shouldHover);

        // Em dispositivos touch, gerencia o posicionamento dos controles e o estado ativo do post.
        if (isTouchPrimary && featuredPost) {
            if (shouldHover) {
                featuredPost.classList.add('is-touch-active');
            } else {

                // Verifica se algum slide ainda está ativo antes de remover o estado ativo do post.
                const hasActiveSlide = Array.from(featuredSlides).some(activeSlide =>
                    activeSlide.classList.contains(FEATURED_HOVER_CLASS)
                );

                if (!hasActiveSlide) {
                    featuredPost.classList.remove('is-touch-active');
                }
            }
        }
    });
}

/**
 * 
 * @param {number} index - O índice do slide a ser exibido.
 * @param {boolean} preserveHover - Verdadeiro para preservar o estado de hover.
 */

function showFeaturedSlide(index, preserveHover = isFeaturedHoverLocked) {

    // Calcula a largura do slide para determinar a distância de translação.
    const slideWidth = featuredSlides[0].offsetWidth;

    // Move o container usando a distância calculada para mostrar o slide correto.
    featuredContainer.style.transform = `translateX(-${index * slideWidth}px)`;
    
    // Atualiza os controles para refletir o slide ativo.
    featuredDots.forEach(dot => dot.classList.remove('active'));
    featuredDots[index].classList.add('active');
    
    // Atualiza o índice atual do slide.
    currentFeaturedIndex = index;

    // Limpa qualquer estado de hover existente.
    clearFeaturedHover();

    // Se hover deve ser preservado (preserveHover = true, isFeaturedHoverLocked = true), reaplica o hover ao slide atual.
    if (preserveHover) {
        setFeaturedHover(currentFeaturedIndex, true);
    }
}

// Avança para o próximo slide em sequência, reiniciando para o primeiro após o último.

function nextFeaturedSlide() {
    currentFeaturedIndex = (currentFeaturedIndex + 1) % featuredSlides.length;
    showFeaturedSlide(currentFeaturedIndex);
}

//Inicia a reprodução automática dos slides em destaque.

function startFeaturedAutoPlay() {
    stopFeaturedAutoPlay();
    featuredAutoPlayInterval = setInterval(nextFeaturedSlide, FEATURED_AUTO_PLAY_DELAY);
}

//Para a reprodução automática dos slides em destaque.

function stopFeaturedAutoPlay() {
    if (featuredAutoPlayInterval) {
        clearInterval(featuredAutoPlayInterval);
    }
}

// Adiciona um listener de clique para cada ponto do controle.
featuredDots.forEach((dot, index) => {
    dot.addEventListener('click', () => {

        // Verifica se o slide atual está em hover, preserva esse estado ao alternar.
        const shouldLockHover = featuredSlides[currentFeaturedIndex].classList.contains(FEATURED_HOVER_CLASS) ||
            featuredSlides[currentFeaturedIndex].matches(':hover');
        isFeaturedHoverLocked = shouldLockHover;

        // Exibe o slide correspondente e reinicia o autoplay.
        showFeaturedSlide(index, isFeaturedHoverLocked);

        // Reinicia o timer do autoplay após a interação do usuário.
        stopFeaturedAutoPlay();
        startFeaturedAutoPlay();
    });
});


// Se o container do controle existir, adiciona listeners para eventos com o mouse.
if (featuredDotsContainer) {

    // Quando o mouse entra, aplica o hover ao slide atual, mostrando a descrição do card e mudando a posição do controle.
    featuredDotsContainer.addEventListener('mouseenter', () => {
        if (!isFeaturedHoverLocked) {
            setFeaturedHover(currentFeaturedIndex, true);
        }
    });

    // Quando o mouse sai, remove o hover do slide atual e esconde a descrição , a menos que o hover esteja travado.
    featuredDotsContainer.addEventListener('mouseleave', event => {
        const nextTarget = event.relatedTarget;
        if (nextTarget && (featuredDotsContainer.contains(nextTarget) || featuredSlides[currentFeaturedIndex].contains(nextTarget))) {
            return;
        }
        if (isFeaturedHoverLocked) {
            return;
        }
        setFeaturedHover(currentFeaturedIndex, false, nextTarget);
    });
}

// Pause o autoplay quando o mouse está sobre o post em destaque.
featuredPost.addEventListener('mouseenter', () => {
    stopFeaturedAutoPlay();
});

// Retoma o autoplay quando o mouse sai do post em destaque.
featuredPost.addEventListener('mouseleave', () => {
    isFeaturedHoverLocked = false;
    clearFeaturedHover();
    startFeaturedAutoPlay();
});

// Ajusta a exibição do slide em destaque ao redimensionar a janela.
window.addEventListener('resize', () => {
    showFeaturedSlide(currentFeaturedIndex, isFeaturedHoverLocked);
});

// Checa se existem slides antes de iniciar o carrossel.
if (featuredSlides.length > 0) {
    showFeaturedSlide(0, false);
    startFeaturedAutoPlay();

    // Habilita o comportamento de toque para alternar slides em dispositivos de mobiles.
    if (isTouchPrimary) {
        featuredSlides.forEach(slide => {
            slide.addEventListener('click', event => {
                event.preventDefault();
                event.stopPropagation();

                const isActive = slide.classList.contains(FEATURED_HOVER_CLASS);

                // Se o slide já está ativo, remova o hover e retome o autoplay.
                if (isActive) {
                    slide.classList.remove(FEATURED_HOVER_CLASS);
                    isFeaturedHoverLocked = false;
                    featuredPost.classList.remove('is-touch-active');
                    startFeaturedAutoPlay();
                    return;
                }

                // Se o slide não está ativo, limpe qualquer hover existente, aplique o hover ao slide atual e pare o autoplay.
                clearFeaturedHover();
                featuredPost.classList.add('is-touch-active');
                slide.classList.add(FEATURED_HOVER_CLASS);
                isFeaturedHoverLocked = true;
                stopFeaturedAutoPlay();
            });
        });

        // Adiciona um listener global para detectar toques fora do post em destaque e desativar o hover.
        document.addEventListener('click', event => {
            if (!isFeaturedHoverLocked) {
                return;
            }

            if (!featuredPost.contains(event.target)) {
                clearFeaturedHover();
                featuredPost.classList.remove('is-touch-active');
                isFeaturedHoverLocked = false;
                startFeaturedAutoPlay();
            }
        });
    }
}

// Seleciona os elementos HTML necessários para o carrossel de posts recentes.
const carousel = document.querySelector('.carousel');
const carouselItems = document.querySelectorAll('.carousel-item');
const leftArrow = document.querySelector('.left-arrow');
const rightArrow = document.querySelector('.right-arrow');
const carouselControls = document.querySelector('.carousel-controls');
const CAROUSEL_ACTIVE_CLASS = 'is-active';

const itemsPerPage = 3; // Quantos items por página
const itemsPerPageMobile = 2;
const totalPages = Math.ceil(carouselItems.length / itemsPerPage);
const totalPagesMobile = Math.ceil(carouselItems.length / itemsPerPageMobile);
let dots = []; // Array vazio
let isMobileView = window.matchMedia("(max-width: 480px)").matches;
const pages = isMobileView ? totalPagesMobile : totalPages;

if (carouselControls && rightArrow && pages > 0) {
    for (let i = 0; i < pages; i++) {
        const dot = document.createElement('span');
        dot.classList.add('dot');
        if (i === 0) {
            dot.classList.add('active');
        }
        // Coloca os labels entre as setas
        carouselControls.insertBefore(dot, rightArrow);
    }
    
    dots = document.querySelectorAll('.dot');
}

let currentIndex = 0; // Índice da PÁGINA (desktop) ou ITEM (mobile)
let activeCarouselItem = null;


// Retira o estado ativo de todos os itens do carrossel.
function clearCarouselActiveState() {
    if (carouselItems.length === 0) {
        return;
    }
    carouselItems.forEach(item => item.classList.remove(CAROUSEL_ACTIVE_CLASS));
    activeCarouselItem = null;
}

// Atualiza o carrossel de posts recentes para o próximo índice.
function updateCarousel() {
    isMobileView = window.matchMedia("(max-width: 480px)").matches;

    if (isMobileView) {

        // Garante que o índice está dentro dos limites (0 a 8)
        if (currentIndex < 0) currentIndex = totalPagesMobile - 1;
        if (currentIndex >= totalPagesMobile) currentIndex = 0;

        dots.forEach((d, i) => d.classList.toggle('active', i === currentIndex));

        const targetItemIndex = currentIndex * itemsPerPageMobile;

        if (carouselItems[targetItemIndex]) {
            carouselItems[targetItemIndex].scrollIntoView({
                behavior: 'smooth',
                block: 'nearest',
                inline: 'nearest'
            });
        }
    }

    else {
        // Garante que o índice está dentro dos limites (0 a 4)
        if (currentIndex < 0) currentIndex = totalPages - 1;
        if (currentIndex >= totalPages) currentIndex = 0;
        
        // Atualiza os dots
        dots.forEach((d, i) => d.classList.toggle('active', i === currentIndex));

        // Calcula qual item deve ser rolado para a vista
        // (Índice da página * items por página) = índice do primeiro item da página
        const targetItemIndex = currentIndex * itemsPerPage;
        if (carouselItems[targetItemIndex]) {
            carouselItems[targetItemIndex].scrollIntoView({
                behavior: 'smooth',
                block: 'nearest',
                inline: 'nearest'
            });
        }
    }
    
    // Limpa o estado de 'hover' de toque
    clearCarouselActiveState();
}


// Adiciona listeners de clique para a seta esquerda.
leftArrow.addEventListener('click', () => {
    currentIndex--;
    updateCarousel();
});

// Adiciona listeners de clique para a seta direita.
rightArrow.addEventListener('click', () => {
    currentIndex++;
    updateCarousel();
});

// Lógica de toque para controles em dispositivos móveis
if (isTouchPrimary && carouselItems.length > 0) {
    carouselItems.forEach(item => {
        item.addEventListener('click', event => {
            event.preventDefault();
            event.stopPropagation();

            const isActive = item.classList.contains(CAROUSEL_ACTIVE_CLASS);

            // Remove o estado ativo se o item já estiver ativo.
            clearCarouselActiveState();


            // Se o item não estava ativo, aplica o estado ativo a ele.
            if (!isActive) {
                item.classList.add(CAROUSEL_ACTIVE_CLASS);
                activeCarouselItem = item;
            }
        });
    });

    // Adiciona um listener global para detectar toques fora do carrossel e desativar o item ativo.
    document.addEventListener('click', event => {
        if (!activeCarouselItem) {
            return;
        }

        const isInsideCarousel = (carousel && carousel.contains(event.target)) ||
            (carouselControls && carouselControls.contains(event.target));

        if (!isInsideCarousel) {
            clearCarouselActiveState();
        }
    });
}

// Adiciona um evento de clique ao botão de call to action para redirecionar o usuário.
document.querySelector('.cta-button').addEventListener('click', () => {
    window.location.href = "index.html";
});

// Espera o conteúdo da página carregar antes de executar o script.
document.addEventListener('DOMContentLoaded', () => {

    // Trunca descrições longas no carrossel de posts recentes.
    const carouselDescriptions = document.querySelectorAll('.carousel-item-description');
    const maxLength = 200;
    
    carouselDescriptions.forEach(description => {
        const text = description.textContent.trim();
        if (text.length > maxLength) {
            description.textContent = text.substring(0, maxLength).trim() + '...';
        }
    });

    // Trunca descrições longas no carrossel de posts em destaque.
    const featuredDescriptions = document.querySelectorAll('.featured-description');
    const featuredMaxLength = 200;
    
    featuredDescriptions.forEach(description => {
        const text = description.textContent.trim();
        if (text.length > featuredMaxLength) {
            description.textContent = text.substring(0, featuredMaxLength).trim() + '...';
        }
    });
});