document.addEventListener('DOMContentLoaded', function() {
    const sliders = document.querySelectorAll('.galigeo-slider');

    sliders.forEach(slider => {
        const container = slider.querySelector('.galigeo-slider-container');
        const slides = Array.from(slider.querySelectorAll('.galigeo-slide'));
        const prevBtn = slider.querySelector('.galigeo-slider-prev');
        const nextBtn = slider.querySelector('.galigeo-slider-next');
        let currentSlide = 1; // Set to 1 since we are cloning slides
        const slideCount = slides.length;

        // Clone the first and last slides
        const firstSlideClone = slides[0].cloneNode(true);
        const lastSlideClone = slides[slideCount - 1].cloneNode(true);

        // Append and prepend cloned slides
        container.appendChild(firstSlideClone);
        container.insertBefore(lastSlideClone, slides[0]);

        // Update the slides array after adding the clones
        const allSlides = Array.from(container.querySelectorAll('.galigeo-slide'));

        function goToSlide(index) {
            // Adjust the index for the cloned slides
            container.style.transition = 'transform 0.5s ease';
            container.style.transform = `translateX(${-index * 104}%)`;
            currentSlide = index;

            // Disable transition temporarily to create the loop effect
            if (currentSlide === 0) {
                setTimeout(() => {
                    container.style.transition = 'none';
                    container.style.transform = `translateX(${-slideCount * 104}%)`;
                    currentSlide = slideCount;
                }, 500);
            } else if (currentSlide === slideCount + 1) {
                setTimeout(() => {
                    container.style.transition = 'none';
                    container.style.transform = `translateX(-104%)`;
                    currentSlide = 1;
                }, 500);
            }

            updateActiveSlide();
        }

        function updateActiveSlide() {
            allSlides.forEach((slide, index) => {
                if (index === currentSlide) {
                    slide.classList.add('active');
                } else {
                    slide.classList.remove('active');
                }
            });
        }

        function nextSlide() {
            goToSlide(currentSlide + 1);
        }

        function prevSlide() {
            goToSlide(currentSlide - 1);
        }

        prevBtn.addEventListener('click', prevSlide);
        nextBtn.addEventListener('click', nextSlide);

        // Initialize to the first real slide
        container.style.transform = `translateX(-104%)`;

        // Auto-play
        setInterval(nextSlide, 6000);

        // Initialisation
        updateActiveSlide();
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const sliders = document.querySelectorAll('.galigeo-slider-home');

    sliders.forEach(slider => {
        const container = slider.querySelector('.galigeo-slider-home-container');
        const slides = Array.from(slider.querySelectorAll('.galigeo-slide-home'));
        const prevBtn = slider.querySelector('.galigeo-slider-home-prev');
        const nextBtn = slider.querySelector('.galigeo-slider-home-next');
        let currentSlide = 1; // Set to 1 since we are cloning slides
        const slideCount = slides.length;

        // Clone the first and last slides
        const firstSlideClone = slides[0].cloneNode(true);
        const lastSlideClone = slides[slideCount - 1].cloneNode(true);

        // Append and prepend cloned slides
        container.appendChild(firstSlideClone);
        container.insertBefore(lastSlideClone, slides[0]);

        // Update the slides array after adding the clones
        const allSlides = Array.from(container.querySelectorAll('.galigeo-slide-home'));

        function goToSlide(index) {
            // Adjust the index for the cloned slides
            container.style.transition = 'transform 0.5s ease';
            container.style.transform = `translateX(${-index * 100}%)`;
            currentSlide = index;

            // Disable transition temporarily to create the loop effect
            if (currentSlide === 0) {
                setTimeout(() => {
                    container.style.transition = 'none';
                    container.style.transform = `translateX(${-slideCount * 100}%)`;
                    currentSlide = slideCount;
                }, 500);
            } else if (currentSlide === slideCount + 1) {
                setTimeout(() => {
                    container.style.transition = 'none';
                    container.style.transform = `translateX(-100%)`;
                    currentSlide = 1;
                }, 500);
            }

            updateActiveSlide();
        }

        function updateActiveSlide() {
            allSlides.forEach((slide, index) => {
                if (index === currentSlide) {
                    slide.classList.add('active');
                } else {
                    slide.classList.remove('active');
                }
            });
        }

        function nextSlide() {
            goToSlide(currentSlide + 1);
        }

        function prevSlide() {
            goToSlide(currentSlide - 1);
        }

        prevBtn.addEventListener('click', prevSlide);
        nextBtn.addEventListener('click', nextSlide);

        // Initialize to the first real slide
        container.style.transform = `translateX(-100%)`;

        // Auto-play
        setInterval(nextSlide, 6000);

        // Initialisation
        updateActiveSlide();
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const sliders = document.querySelectorAll('.testimonials-slider');

    sliders.forEach(slider => {
        const container = slider.querySelector('.testimonials-slider-container');
        const slides = Array.from(slider.querySelectorAll('.testimonial-slide'));
        const prevBtn = slider.querySelector('.testimonials-slider-prev');
        const nextBtn = slider.querySelector('.testimonials-slider-next');
        let currentSlide = 1; // Set to 1 since we are cloning slides
        const slideCount = slides.length;

        // Clone the first and last slides
        const firstSlideClone = slides[0].cloneNode(true);
        const lastSlideClone = slides[slideCount - 1].cloneNode(true);

        // Append and prepend cloned slides
        container.appendChild(firstSlideClone);
        container.insertBefore(lastSlideClone, slides[0]);

        // Update the slides array after adding the clones
        const allSlides = Array.from(container.querySelectorAll('.testimonial-slide'));

        function goToSlide(index) {
            // Adjust the index for the cloned slides
            container.style.transition = 'transform 0.5s ease';
            container.style.transform = `translateX(${-index * 100}%)`;
            currentSlide = index;

            // Disable transition temporarily to create the loop effect
            if (currentSlide === 0) {
                setTimeout(() => {
                    container.style.transition = 'none';
                    container.style.transform = `translateX(${-slideCount * 100}%)`;
                    currentSlide = slideCount;
                }, 500);
            } else if (currentSlide === slideCount + 1) {
                setTimeout(() => {
                    container.style.transition = 'none';
                    container.style.transform = `translateX(-100%)`;
                    currentSlide = 1;
                }, 500);
            }

            updateActiveSlide();
        }

        function updateActiveSlide() {
            allSlides.forEach((slide, index) => {
                if (index === currentSlide) {
                    slide.classList.add('active');
                } else {
                    slide.classList.remove('active');
                }
            });
        }

        function nextSlide() {
            goToSlide(currentSlide + 1);
        }

        function prevSlide() {
            goToSlide(currentSlide - 1);
        }

        prevBtn.addEventListener('click', prevSlide);
        nextBtn.addEventListener('click', nextSlide);

        // Initialize to the first real slide
        container.style.transform = `translateX(-100%)`;

        // Auto-play
        setInterval(nextSlide, 6000);

        // Initialisation
        updateActiveSlide();
    });
});

document.addEventListener('DOMContentLoaded', function() {
    function initCarousel() {
        const carousels = document.querySelectorAll('.galigeo-logo-carousel');
        
        carousels.forEach(carousel => {
            const container = carousel.querySelector('.galigeo-logo-carousel-container');
            const slides = Array.from(carousel.querySelectorAll('.galigeo-logo-carousel-slide'));
            const prevBtn = carousel.querySelector('.galigeo-logo-carousel-prev');
            const nextBtn = carousel.querySelector('.galigeo-logo-carousel-next');
            
            let slidesPerView = getSlidesPerView();
            let slideWidth = 100 / slidesPerView;
            let autoplayInterval = null;
            
            function setupSlider() {
                const clonesStart = slides.slice(-slidesPerView).map(slide => slide.cloneNode(true));
                const clonesEnd = slides.slice(0, slidesPerView).map(slide => slide.cloneNode(true));
                
                container.innerHTML = '';
                [...clonesStart, ...slides, ...clonesEnd].forEach(slide => {
                    slide.style.flex = `0 0 ${slideWidth}%`;
                    container.appendChild(slide);
                });
                
                updatePosition(-slidesPerView * slideWidth);
            }
            
            function getSlidesPerView() {
                if (window.innerWidth >= 1024) return 5;
                if (window.innerWidth >= 768) return 3;
                return 1;
            }
            
            let currentTranslate = -slidesPerView * slideWidth;
            let isTransitioning = false;
            
            function updatePosition(translate, smooth = false) {
                container.style.transition = smooth ? 'transform 0.3s ease' : 'none';
                container.style.transform = `translateX(${translate}%)`;
                currentTranslate = translate;
            }
            
            function slideNext() {
                if (isTransitioning) return;
                isTransitioning = true;
                
                const nextTranslate = currentTranslate - slideWidth;
                updatePosition(nextTranslate, true);
                
                setTimeout(() => {
                    if (nextTranslate <= -(slides.length + slidesPerView) * slideWidth) {
                        updatePosition(-slidesPerView * slideWidth);
                    }
                    isTransitioning = false;
                }, 300);
            }
            
            function slidePrev() {
                if (isTransitioning) return;
                isTransitioning = true;
                
                const nextTranslate = currentTranslate + slideWidth;
                updatePosition(nextTranslate, true);
                
                setTimeout(() => {
                    if (nextTranslate > -slidesPerView * slideWidth) {
                        updatePosition(-(slides.length) * slideWidth);
                    }
                    isTransitioning = false;
                }, 300);
            }
            
            // Démarrer le défilement automatique
            function startAutoplay() {
                if (autoplayInterval) return;
                autoplayInterval = setInterval(slideNext, 3000);
            }
            
            // Arrêter le défilement automatique
            function stopAutoplay() {
                if (autoplayInterval) {
                    clearInterval(autoplayInterval);
                    autoplayInterval = null;
                }
            }
            
            // Event Listeners
            prevBtn.addEventListener('click', () => {
                slidePrev();
                stopAutoplay(); // Arrêter l'autoplay lors d'un clic manuel
            });
            
            nextBtn.addEventListener('click', () => {
                slideNext();
                stopAutoplay(); // Arrêter l'autoplay lors d'un clic manuel
            });
            
            // Arrêter l'autoplay au survol
            carousel.addEventListener('mouseenter', stopAutoplay);
            
            // Reprendre l'autoplay quand la souris quitte le carousel
            carousel.addEventListener('mouseleave', startAutoplay);
            
            // Gestion du responsive
            window.addEventListener('resize', () => {
                const newSlidesPerView = getSlidesPerView();
                if (newSlidesPerView !== slidesPerView) {
                    slidesPerView = newSlidesPerView;
                    slideWidth = 100 / slidesPerView;
                    setupSlider();
                }
            });
            
            // Setup initial
            setupSlider();
            startAutoplay(); // Démarrer l'autoplay au chargement
        });
    }
    
    initCarousel();
});