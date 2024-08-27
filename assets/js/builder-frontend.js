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
