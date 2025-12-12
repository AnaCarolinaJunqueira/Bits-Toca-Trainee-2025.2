const featuredContainer = document.querySelector('.featured-container');
const featuredSlides = document.querySelectorAll('.featured-slide');
const featuredDotsContainer = document.querySelector('.featured-dots');
const featuredPost = document.querySelector('.featured-post');

/* ---------------- FEATURED POSTS LOGIC ---------------- */
let featuredDots = [];

if (featuredDotsContainer && featuredSlides.length > 0) {
    featuredDotsContainer.innerHTML = '';

    featuredSlides.forEach((slide, index) => {
        const dot = document.createElement('span');
        dot.classList.add('featured-dot');
        if (index === 0) {
            dot.classList.add('active');
        }
        featuredDotsContainer.appendChild(dot);
    });

    featuredDots = document.querySelectorAll('.featured-dot');
}

let currentFeaturedIndex = 0;
let featuredAutoPlayInterval;
const FEATURED_AUTO_PLAY_DELAY = 3000;
const FEATURED_HOVER_CLASS = 'is-hovered';
let isFeaturedHoverLocked = false;
const isTouchPrimary = window.matchMedia('(hover: none)').matches || 'ontouchstart' in window;

function clearFeaturedHover() {
    featuredSlides.forEach(slide => slide.classList.remove(FEATURED_HOVER_CLASS));

    if (isTouchPrimary && featuredPost) {
        featuredPost.classList.remove('is-touch-active');
    }
}

function setFeaturedHover(index, shouldHover, relatedTarget) {
    featuredSlides.forEach((slide, slideIndex) => {
        if (slideIndex !== index) {
            if (shouldHover) {
                slide.classList.remove(FEATURED_HOVER_CLASS);
            }
            return;
        }

        if (!shouldHover && relatedTarget && slide.contains(relatedTarget)) {
            return;
        }

        slide.classList.toggle(FEATURED_HOVER_CLASS, shouldHover);

        if (isTouchPrimary && featuredPost) {
            if (shouldHover) {
                featuredPost.classList.add('is-touch-active');
            } else {
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

function showFeaturedSlide(index, preserveHover = isFeaturedHoverLocked) {
    const slideWidth = featuredSlides[0].offsetWidth;

    featuredContainer.style.transform = `translateX(-${index * slideWidth}px)`;
    
    if(featuredDots.length > 0) {
        featuredDots.forEach(dot => dot.classList.remove('active'));
        if(featuredDots[index]) featuredDots[index].classList.add('active');
    }
    
    currentFeaturedIndex = index;

    clearFeaturedHover();

    if (preserveHover) {
        setFeaturedHover(currentFeaturedIndex, true);
    }
}

function nextFeaturedSlide() {
    currentFeaturedIndex = (currentFeaturedIndex + 1) % featuredSlides.length;
    showFeaturedSlide(currentFeaturedIndex);
}

function startFeaturedAutoPlay() {
    stopFeaturedAutoPlay();
    featuredAutoPlayInterval = setInterval(nextFeaturedSlide, FEATURED_AUTO_PLAY_DELAY);
}

function stopFeaturedAutoPlay() {
    if (featuredAutoPlayInterval) {
        clearInterval(featuredAutoPlayInterval);
    }
}

if(featuredDots.length > 0) {
    featuredDots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            const shouldLockHover = featuredSlides[currentFeaturedIndex].classList.contains(FEATURED_HOVER_CLASS) ||
                featuredSlides[currentFeaturedIndex].matches(':hover');
            isFeaturedHoverLocked = shouldLockHover;

            showFeaturedSlide(index, isFeaturedHoverLocked);

            stopFeaturedAutoPlay();
            startFeaturedAutoPlay();
        });
    });
}

if (featuredDotsContainer) {
    featuredDotsContainer.addEventListener('mouseenter', () => {
        if (!isFeaturedHoverLocked) {
            setFeaturedHover(currentFeaturedIndex, true);
        }
    });

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

if (featuredPost) {
    featuredPost.addEventListener('mouseenter', () => {
        stopFeaturedAutoPlay();
    });

    featuredPost.addEventListener('mouseleave', () => {
        isFeaturedHoverLocked = false;
        clearFeaturedHover();
        startFeaturedAutoPlay();
    });
}

if (featuredSlides.length > 0) {
    showFeaturedSlide(0, false);
    startFeaturedAutoPlay();

    if (isTouchPrimary) {
        featuredSlides.forEach(slide => {
            slide.addEventListener('click', event => {
                event.preventDefault();
                event.stopPropagation();

                const isActive = slide.classList.contains(FEATURED_HOVER_CLASS);

                if (isActive) {
                    slide.classList.remove(FEATURED_HOVER_CLASS);
                    isFeaturedHoverLocked = false;
                    featuredPost.classList.remove('is-touch-active');
                    startFeaturedAutoPlay();
                    return;
                }

                clearFeaturedHover();
                featuredPost.classList.add('is-touch-active');
                slide.classList.add(FEATURED_HOVER_CLASS);
                isFeaturedHoverLocked = true;
                stopFeaturedAutoPlay();
            });
        });

        document.addEventListener('click', event => {
            if (!isFeaturedHoverLocked) {
                return;
            }

            if (featuredPost && !featuredPost.contains(event.target)) {
                clearFeaturedHover();
                featuredPost.classList.remove('is-touch-active');
                isFeaturedHoverLocked = false;
                startFeaturedAutoPlay();
            }
        });
    }
}

/* ---------------- CAROUSEL LOGIC ---------------- */

const carousel = document.querySelector('.carousel');
const carouselItems = document.querySelectorAll('.carousel-item');
const leftArrow = document.querySelector('.left-arrow');
const rightArrow = document.querySelector('.right-arrow');
const carouselDotsContainer = document.querySelector('.carousel-dots');
const carouselCounter = document.querySelector('.carousel-counter');
const CAROUSEL_ACTIVE_CLASS = 'is-active';

let currentIndex = 0;
let itemsPerPage = 3;
let totalPages = 0;
let dots = [];

function setupCarousel() {
    const isMobileView = window.matchMedia("(max-width: 768px)").matches;
    
    itemsPerPage = isMobileView ? 2 : 3;
    
    if (carouselItems.length > 0) {
        totalPages = Math.ceil(carouselItems.length / itemsPerPage);
    } else {
        totalPages = 0;
    }

    if (currentIndex >= totalPages) {
        currentIndex = 0;
    }

    if (carouselDotsContainer) {
        carouselDotsContainer.innerHTML = '';
        if (totalPages > 0) {
            for (let i = 0; i < totalPages; i++) {
                const dot = document.createElement('span');
                dot.classList.add('dot');
                if (i === currentIndex) {
                    dot.classList.add('active');
                }
                dot.addEventListener('click', () => {
                    currentIndex = i;
                    updateCarouselPosition();
                });
                carouselDotsContainer.appendChild(dot);
            }
            dots = carouselDotsContainer.querySelectorAll('.dot');
        }
    }
    
    updateCarouselCounter();
    updateCarouselPosition();
}

function updateCarouselCounter() {
    if (carouselCounter) {
        carouselCounter.textContent = `${currentIndex + 1}/${totalPages}`;
    }
}

function clearCarouselActiveState() {
    if (carouselItems.length === 0) return;
    carouselItems.forEach(item => item.classList.remove(CAROUSEL_ACTIVE_CLASS));
}

function updateCarouselPosition() {
    if(dots.length > 0) {
        dots.forEach((d, i) => {
            if (i === currentIndex) d.classList.add('active');
            else d.classList.remove('active');
        });
    }

    const targetItemIndex = currentIndex * itemsPerPage;
    
    if (carouselItems[targetItemIndex]) {
        carouselItems[targetItemIndex].scrollIntoView({
            behavior: 'smooth',
            block: 'nearest',
            inline: 'start'
        });
    }
    
    updateCarouselCounter();
    clearCarouselActiveState();
}

if (leftArrow && rightArrow) {
    leftArrow.addEventListener('click', () => {
        currentIndex--;
        if (currentIndex < 0) currentIndex = totalPages - 1;
        updateCarouselPosition();
    });

    rightArrow.addEventListener('click', () => {
        currentIndex++;
        if (currentIndex >= totalPages) currentIndex = 0;
        updateCarouselPosition();
    });
}

if (carouselCounter) {
    carouselCounter.addEventListener('click', openCarouselGotoModal);
}

if (isTouchPrimary && carouselItems.length > 0) {
    carouselItems.forEach(item => {
        item.addEventListener('click', event => {
            event.preventDefault();
            event.stopPropagation();
            const isActive = item.classList.contains(CAROUSEL_ACTIVE_CLASS);
            clearCarouselActiveState();
            if (!isActive) {
                item.classList.add(CAROUSEL_ACTIVE_CLASS);
            }
        });
    });

    document.addEventListener('click', event => {
        const carouselControls = document.querySelector('.carousel-controls');
        const isInsideCarousel = (carousel && carousel.contains(event.target)) ||
            (carouselControls && carouselControls.contains(event.target));
        if (!isInsideCarousel) clearCarouselActiveState();
    });
}

function openCarouselGotoModal() {
    const modal = document.getElementById('modal-goto-page');
    const input = document.getElementById('goto-page-input');
    const label = document.getElementById('goto-page-label');
    const errorEl = document.getElementById('goto-page-error');
    
    const oldBtn = document.getElementById('btn-confirm-goto');
    if (!oldBtn) return;
    
    const newBtn = oldBtn.cloneNode(true);
    oldBtn.parentNode.replaceChild(newBtn, oldBtn);

    if(modal) {
        modal.dataset.totalPages = totalPages;
        abrirModal('modal-goto-page');
    }
    
    if(input) {
        input.max = totalPages;
        input.value = '';
    }
    
    if(label) label.textContent = `Digite uma página (1 - ${totalPages})`;
    if(errorEl) errorEl.style.display = 'none';
    
    setTimeout(() => { if(input) input.focus() }, 100);

    newBtn.addEventListener('click', () => {
        const pageNumber = parseInt(input.value);
        
        if (isNaN(pageNumber) || pageNumber < 1 || pageNumber > totalPages) {
            if(errorEl) {
                errorEl.textContent = `Por favor, digite um número entre 1 e ${totalPages}.`;
                errorEl.style.display = 'block';
            }
            if(input) input.focus();
        } else {
            if(errorEl) errorEl.style.display = 'none';
            currentIndex = pageNumber - 1;
            updateCarouselPosition();
            fecharModal('modal-goto-page');
        }
    });
}

const ctaButton = document.querySelector('.cta-button');
if (ctaButton) {
    ctaButton.addEventListener('click', () => {
        window.location.href = "/posts";
    });
}

function handleTextTruncation() {
    const isMobileSmall = window.matchMedia("(max-width: 768px)").matches;
    const maxLength = isMobileSmall ? 80 : 150; 
    const featuredMaxLength = isMobileSmall ? 100 : 200;

    const carouselDescriptions = document.querySelectorAll('.carousel-item-description');
    carouselDescriptions.forEach(description => {
        const fullText = description.dataset.fullText;
        if (fullText) {
            if (fullText.length > maxLength) {
                description.textContent = fullText.substring(0, maxLength).trim() + '...';
            } else {
                description.textContent = fullText;
            }
        }
    });

    const featuredDescriptions = document.querySelectorAll('.featured-description');
    featuredDescriptions.forEach(description => {
        const fullText = description.dataset.fullText;
        if (fullText) {
            if (fullText.length > featuredMaxLength) {
                description.textContent = fullText.substring(0, featuredMaxLength).trim() + '...';
            } else {
                description.textContent = fullText;
            }
        }
    });
}

window.addEventListener('resize', () => {
    showFeaturedSlide(currentFeaturedIndex, isFeaturedHoverLocked);
    setupCarousel();
    handleTextTruncation();
});

document.addEventListener('DOMContentLoaded', () => {
    setupCarousel();
    handleTextTruncation();
});