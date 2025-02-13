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

        // Assign unique indices to cloned slides
        firstSlideClone.dataset.index = slideCount; // Unique index for the first clone
        lastSlideClone.dataset.index = -1; // Unique index for the last clone

        // Append and prepend cloned slides
        container.appendChild(firstSlideClone);
        container.insertBefore(lastSlideClone, slides[0]);

        // Update the slides array after adding the clones
        const allSlides = Array.from(container.querySelectorAll('.galigeo-slide'));

        function goToSlide(index) {
            container.style.transition = 'transform 0.5s ease';
            container.style.transform = `translateX(${-index * 104}%)`;
            currentSlide = index;

            // Handle infinite loop effect
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
                const realIndex = parseInt(slide.dataset.index, 10); // Use dataset index for unique identification
                if (realIndex === currentSlide) {
                    slide.classList.add('active');
                    // slide.style.transform = 'scale(1)';
                } else {
                    slide.classList.remove('active');
                    // slide.style.transform = 'scale(0.9)';
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


document.addEventListener('DOMContentLoaded', function () {
    const sliders = document.querySelectorAll('.galigeo-slider-home');

    // Configuration des paramètres
    const autoplayDelay = 5000; // Délai en millisecondes entre chaque slide (autoplay)
    const enableAutoplay = true; // Activer/désactiver l'autoplay (true ou false)

    sliders.forEach(slider => {
        const container = slider.querySelector('.galigeo-slider-home-container');
        const slides = Array.from(container.querySelectorAll('.galigeo-slide-home'));
        const prevBtn = slider.querySelector('.galigeo-slider-home-prev');
        const nextBtn = slider.querySelector('.galigeo-slider-home-next');
        let currentSlide = 1; // Indice du premier slide réel
        const slideCount = slides.length;

        // Clone le premier et dernier slide
        const firstSlideClone = slides[0].cloneNode(true);
        const lastSlideClone = slides[slideCount - 1].cloneNode(true);

        // Ajouter les clones au container
        container.appendChild(firstSlideClone);
        container.insertBefore(lastSlideClone, slides[0]);

        // Ajuste la position initiale
        container.style.transform = `translateX(-100%)`;

        let autoPlayInterval;

        function startAutoplay() {
            if (enableAutoplay) {
                stopAutoplay(); // Arrêter tout autoplay en cours
                autoPlayInterval = setInterval(nextSlide, autoplayDelay);
            }
        }

        function stopAutoplay() {
            clearInterval(autoPlayInterval);
        }

        function resetAutoplay() {
            stopAutoplay();
            startAutoplay();
        }

        function goToSlide(index) {
            container.style.transition = 'transform 0.5s ease';
            container.style.transform = `translateX(${-index * 100}%)`;
            currentSlide = index;

            // Bouclage des slides
            if (currentSlide === 0) {
                setTimeout(() => {
                    container.style.transition = 'none';
                    container.style.transform = `translateX(${-slideCount * 100}%)`;
                    currentSlide = slideCount;
                }, 500); // Temps pour la transition de retour à la fin
            } else if (currentSlide === slideCount + 1) {
                setTimeout(() => {
                    container.style.transition = 'none';
                    container.style.transform = `translateX(-100%)`;
                    currentSlide = 1;
                }, 500); // Temps pour la transition de retour au début
            }
        }

        function nextSlide() {
            if (currentSlide === slideCount + 1) {
                setTimeout(() => goToSlide(currentSlide), autoplayDelay); // Attendre avant de revenir au premier slide
            } else {
                goToSlide(currentSlide + 1);
            }
        }

        function prevSlide() {
            goToSlide(currentSlide - 1);
        }

        // Ajouter des événements pour les boutons de navigation
        prevBtn.addEventListener('click', () => {
            prevSlide();
            resetAutoplay();
        });

        nextBtn.addEventListener('click', () => {
            nextSlide();
            resetAutoplay();
        });

        // Auto-play
        if (enableAutoplay) {
            startAutoplay();

            // Pause l'autoplay au survol du slider
            slider.addEventListener('mouseover', stopAutoplay);
            slider.addEventListener('mouseout', startAutoplay);
        }

        // Initialisation
        container.style.transition = 'none';
        container.style.transform = `translateX(-100%)`;
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
        setInterval(nextSlide, 8000);

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