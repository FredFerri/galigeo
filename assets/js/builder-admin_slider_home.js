function admin_slider_home_scripts($, builderContainer) {
    const sliderContainer = $('.galigeo-slide-home-container');
    let currentSlide = 0;
    const totalSlides = sliderContainer.children('.galigeo-slide-home').length;

    // Initialisation des boutons et du slider
    function initializeSlider() {
        updateSlideVisibility();
        updateNavButtons();
    }

    // Mise à jour de la visibilité des slides (affiche seulement le slide actif)
    function updateSlideVisibility() {
        sliderContainer.children('.galigeo-slide-home').each(function(index) {
            if (index === currentSlide) {
                $(this).addClass('active');
                $(this).css('transform', 'translateX(0)');
            } else {
                $(this).removeClass('active');
                const translateValue = (index - currentSlide) * 100;
                $(this).css('transform', `translateX(${translateValue}%)`);
            }
        });
    }


    // Mise à jour des boutons de navigation : désactiver si un seul slide
    function updateNavButtons() {
        if (totalSlides <= 1) {
            $('.galigeo-slide-home-prev, .galigeo-slide-home-next').hide();
        } else {
            $('.galigeo-slide-home-prev, .galigeo-slide-home-next').show();
        }
    }

    // Ajout de slides dynamiquement (exemple d'ajout de slides comme dans ton code initial)
    builderContainer.on('click', '.add-slide-home', function(e) {
        e.preventDefault();

        const sliderContainer = $(this).siblings('.slider-home-slides');
        const blockIndex = sliderContainer.data('block-index');
        const slideCount = sliderContainer.children().length;

        if (slideCount < 4) {
            const newSlide = $('#slide-home-template').html()
                .replace(/BLOCK_INDEX/g, blockIndex)
                .replace(/SLIDE_INDEX/g, slideCount);
            
            sliderContainer.append(newSlide);
            updateSlideButtons(sliderContainer);
            updateSlideNumbers(sliderContainer);
        }
    });

    // Suppression de slides
    builderContainer.on('click', '.remove-slide-home', function(e) {
        e.preventDefault();
        const slideContainer = $(this).closest('.slide-home-fields');
        const sliderContainer = slideContainer.parent();
        slideContainer.remove();
        updateSlideButtons(sliderContainer);
        updateSlideNumbers(sliderContainer);
    });

    // Fonction pour gérer le type de background (image ou couleur)
    builderContainer.on('change', '.bg-type-select', function() {
        const bgType = $(this).val();
        const slideField = $(this).closest('.slide-home-fields');
        slideField.find('.bg-color-field').toggle(bgType === 'color');
        slideField.find('.bg-image-field').toggle(bgType === 'image');
    });

    // Gestion de l'affichage du bouton dans le slide
    builderContainer.on('change', 'input[name$="[show_button]"]', function() {
        $(this).closest('.slide-home-fields').find('.button-options').toggle(this.checked);
    });

    function updateSlideButtons(sliderContainer) {
        const slideCount = sliderContainer.children().length;
        sliderContainer.siblings('.add-slide-home').prop('disabled', slideCount >= 4);
        sliderContainer.find('.remove-slide-home').toggle(slideCount > 1);
    }

    function updateSlideNumbers(sliderContainer) {
        sliderContainer.children().each(function(index) {
            $(this).find('.slide-home-number').text(index + 1);
            $(this).find('input, select, textarea').each(function() {
                const name = $(this).attr('name');
                if (name) {
                    $(this).attr('name', name.replace(/\[slides\]\[\d+\]/, '[slides][' + index + ']'));
                }
            });
        });
    }    

    // Initialiser le slider à l'affichage
    initializeSlider();
}

/* Gestion de la suppression dynamique des images */
const removeImgButtons = document.querySelectorAll('.slider-home-remove-img');

removeImgButtons.forEach(button => {
    button.addEventListener('click', function () {
        alert('ok');
        const ImgContainer = this.closest('.relative');
        const ImgIndex = this.getAttribute('data-img-index');

        // Masquer l'image et le bouton
        ImgContainer.style.display = 'none';

        // Désactiver l'input correspondant pour qu'il ne soit pas envoyé dans le formulaire
        const hiddenInput = ImgContainer.querySelector('input[type="hidden"]');
        if (hiddenInput) {
            hiddenInput.disabled = true;
        }
    });
});

// Appel de la fonction lorsque la page est prête
// jQuery(document).ready(function($) {
//     const builderContainer = $('#builder-container');
//     admin_slider_home_scripts($, builderContainer);
// });
