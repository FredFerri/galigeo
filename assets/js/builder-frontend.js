document.addEventListener('DOMContentLoaded', function() {
    const sliders = document.querySelectorAll('.builder-slider');

    sliders.forEach(slider => {
        const slides = slider.querySelectorAll('.slider-item');
        const prevButton = slider.querySelector('.prev-slide');
        const nextButton = slider.querySelector('.next-slide');
        let currentSlide = 0;

        function showSlide(n) {
            slides[currentSlide].classList.remove('opacity-100');
            slides[currentSlide].classList.add('opacity-0');
            currentSlide = (n + slides.length) % slides.length;
            slides[currentSlide].classList.remove('opacity-0');
            slides[currentSlide].classList.add('opacity-100');
        }

        prevButton.addEventListener('click', () => showSlide(currentSlide - 1));
        nextButton.addEventListener('click', () => showSlide(currentSlide + 1));

        // Auto-play (optionnel)
        setInterval(() => showSlide(currentSlide + 1), 5000);
    });
});