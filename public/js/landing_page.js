// Featured Post Carousel
const featuredContainer = document.querySelector('.featured-container');
const featuredSlides = document.querySelectorAll('.featured-slide');
const featuredDots = document.querySelectorAll('.featured-dot');
const featuredDotsContainer = document.querySelector('.featured-dots');
const featuredPost = document.querySelector('.featured-post');
let currentFeaturedIndex = 0;
let featuredAutoPlayInterval;
const FEATURED_AUTO_PLAY_DELAY = 5000;
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
    
    featuredDots.forEach(dot => dot.classList.remove('active'));
    featuredDots[index].classList.add('active');
    
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

// Pause autoplay on hover
featuredPost.addEventListener('mouseenter', () => {
    stopFeaturedAutoPlay();
});

// Resume autoplay when mouse leaves
featuredPost.addEventListener('mouseleave', () => {
    isFeaturedHoverLocked = false;
    clearFeaturedHover();
    startFeaturedAutoPlay();
});

// Handle window resize
window.addEventListener('resize', () => {
    showFeaturedSlide(currentFeaturedIndex, isFeaturedHoverLocked);
});

if (featuredSlides.length > 0) {
    showFeaturedSlide(0, false);
    startFeaturedAutoPlay();

    // Enable tap-to-toggle behavior for featured slides on touch devices.
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

            if (!featuredPost.contains(event.target)) {
                clearFeaturedHover();
                featuredPost.classList.remove('is-touch-active');
                isFeaturedHoverLocked = false;
                startFeaturedAutoPlay();
            }
        });
    }
}

// Recent Posts Carousel
const dots = document.querySelectorAll('.dot');
const carouselItems = document.querySelectorAll('.carousel-item');
const leftArrow = document.querySelector('.left-arrow');
const rightArrow = document.querySelector('.right-arrow');
const carousel = document.querySelector('.carousel');
const carouselControls = document.querySelector('.carousel-controls');
const CAROUSEL_ACTIVE_CLASS = 'is-active';

let currentIndex = 0;
const totalItems = dots.length;
let isAnimating = false;
let activeCarouselItem = null;

function clearCarouselActiveState() {
    if (carouselItems.length === 0) {
        return;
    }

    carouselItems.forEach(item => item.classList.remove(CAROUSEL_ACTIVE_CLASS));
    activeCarouselItem = null;
}

function updateCarousel(direction = 'right') {
    if (isAnimating) return;
    isAnimating = true;

    clearCarouselActiveState();

    dots.forEach(d => d.classList.remove('active'));
    dots[currentIndex].classList.add('active');
    
    // Exit animation for items
    carouselItems.forEach(item => {
        item.style.transition = 'transform 0.5s ease-in-out, opacity 0.5s ease-in-out';
        if (direction === 'right') {
            item.style.transform = 'translateX(-100%)';
        } else {
            item.style.transform = 'translateX(100%)';
        }
        item.style.opacity = '0';
    });

    // After exit animation, scroll and animate in
    setTimeout(() => {
        const itemWidth = carouselItems[0].offsetWidth;
        carousel.scrollTo({
            left: currentIndex * itemWidth,
            behavior: 'auto'
        });

        carouselItems.forEach(item => {
            item.style.transition = 'none';
            if (direction === 'right') {
                item.style.transform = 'translateX(100%)';
            } else {
                item.style.transform = 'translateX(-100%)';
            }
        });

        carousel.offsetHeight;

        requestAnimationFrame(() => {
            carouselItems.forEach(item => {
                item.style.transition = 'transform 0.5s ease-in-out, opacity 0.5s ease-in-out';
                item.style.transform = 'translateX(0)';
                item.style.opacity = '1';
            });
        });

        setTimeout(() => {
            isAnimating = false;
        }, 500);
    }, 500);
}

dots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
        const direction = index > currentIndex ? 'right' : 'left';
        currentIndex = index;
        updateCarousel(direction);
    });
});

leftArrow.addEventListener('click', () => {
    currentIndex = (currentIndex - 1 + totalItems) % totalItems;
    updateCarousel('left');
});

rightArrow.addEventListener('click', () => {
    currentIndex = (currentIndex + 1) % totalItems;
    updateCarousel('right');
});

// Mirror hover behavior with tap interactions for carousel cards on touch devices.
if (isTouchPrimary && carouselItems.length > 0) {
    carouselItems.forEach(item => {
        item.addEventListener('click', event => {
            event.preventDefault();
            event.stopPropagation();

            const isActive = item.classList.contains(CAROUSEL_ACTIVE_CLASS);

            clearCarouselActiveState();

            if (!isActive) {
                item.classList.add(CAROUSEL_ACTIVE_CLASS);
                activeCarouselItem = item;
            }
        });
    });

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

// CTA button functionality
document.querySelector('.cta-button').addEventListener('click', () => {
    window.location.href = "index.html";
});

// Truncate carousel descriptions
document.addEventListener('DOMContentLoaded', () => {
    const carouselDescriptions = document.querySelectorAll('.carousel-item-description');
    const maxLength = 280;
    
    carouselDescriptions.forEach(description => {
        const text = description.textContent.trim();
        if (text.length > maxLength) {
            description.textContent = text.substring(0, maxLength).trim() + '...';
        }
    });

    // Truncate featured descriptions
    const featuredDescriptions = document.querySelectorAll('.featured-description');
    const featuredMaxLength = 200;
    
    featuredDescriptions.forEach(description => {
        const text = description.textContent.trim();
        if (text.length > featuredMaxLength) {
            description.textContent = text.substring(0, featuredMaxLength).trim() + '...';
        }
    });
});