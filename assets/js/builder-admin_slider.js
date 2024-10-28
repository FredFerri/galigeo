function admin_slider_scripts($, builderContainer) {
    // Gestion de l'ajout de slide avec débogage
    builderContainer.on('click', '.add-slide', function(e) {
        e.preventDefault();

        const sliderContainer = $(this).siblings('.slider-slides');
        console.dir(sliderContainer);
        if (!sliderContainer.length) {
            console.error("Slider container (.slider-slides) not found.");
            return;
        }

        const blockIndex = sliderContainer.closest('.builder-block').data('index');
        alert(blockIndex);
        if (blockIndex === undefined) {
            console.error("Block index not found on slider container.");
            return;
        }

        const slideCount = sliderContainer.children().length;
        alert(slideCount);
        console.dir(sliderContainer);
        // alert(slideCount);
        console.log("Current slide count:", slideCount);

        if (slideCount < 4) {
            const newSlide = $('#slide-template').html()
                .replace(/BLOCK_INDEX/g, blockIndex)
                .replace(/SLIDE_INDEX/g, slideCount);

            if (!newSlide) {
                console.error("Slide template not found or empty.");
                return;
            }

            sliderContainer.append(newSlide);
            console.log("New slide added successfully.");

            updateSlideButtons(sliderContainer);
            updateSlideNumbers(sliderContainer);
        } else {
            console.warn("Slide limit reached (4 slides).");
        }
    });

    // Gestion de la suppression de slide
    // builderContainer.on('click', '.remove-slide', function(e) {
    //     e.preventDefault();

    //     const sliderContainer = $(this).closest('.slider-slides');
    //     $(this).closest('.slide').remove();

    //     updateSlideButtons(sliderContainer);
    //     updateSlideNumbers(sliderContainer);
    // });


    // Gestion de la suppression de slide
    builderContainer.on('click', '.remove-slide', function(e) {
        e.preventDefault();
        const slideContainer = $(this).closest('.slide-fields');
        const sliderContainer = slideContainer.parent();
        slideContainer.remove();
        updateSlideButtons(sliderContainer);
        updateSlideNumbers(sliderContainer);
    });

    function updateSlideButtons(sliderContainer) {
        const slideCount = sliderContainer.children().length;
        sliderContainer.siblings('.add-slide').prop('disabled', slideCount >= 4);
        sliderContainer.find('.remove-slide').toggle(slideCount > 1);
    }

    function updateSlideNumbers(sliderContainer) {
        sliderContainer.children().each(function(index) {
            $(this).find('.slide-number').text(index + 1);
            $(this).find('input, select, textarea').each(function() {
                const name = $(this).attr('name');
                if (name) {
                    $(this).attr('name', name.replace(/\[slides\]\[\d+\]/, '[slides][' + index + ']'));
                }
            });
        });
    }

    // Gestion du type de background
    builderContainer.on('change', '.bg-type-select', function() {
        const bgType = $(this).val();
        const slideField = $(this).closest('.slide-fields');
        slideField.find('.bg-color-field').toggle(bgType === 'color');
        slideField.find('.bg-image-field').toggle(bgType === 'image');
    });

    // Gestion de l'affichage du bouton
    builderContainer.on('change', 'input[name$="[show_button]"]', function() {
        $(this).closest('.slide-fields, .video-block').find('.button-options').toggle(this.checked);
    });  

    /* Gestion de la suppression dynamique des images */
    const removeImgButtons = document.querySelectorAll('.slider-remove-img');

    removeImgButtons.forEach(button => {
        button.addEventListener('click', function () {
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

    // Gérer le changement du type de média
    $(document).on('change', '.media-type-select', function() {
        const mediaType = $(this).val(); // Récupère le type de média sélectionné
        const mediaField = $(this).closest('.slide-fields').find('.media-field');

        if (mediaType === 'video') {
            // Si la vidéo est sélectionnée, changer le texte du label et accepter les fichiers vidéo
            mediaField.find('label').text('Vidéo :');
            mediaField.find('input[type="file"]').attr('accept', 'video/*');
        } else {
            // Si l'image est sélectionnée, changer le texte du label et accepter les fichiers image
            mediaField.find('label').text('Image :');
            mediaField.find('input[type="file"]').attr('accept', 'image/*');
        }
    });

    // Suppression de l'image ou du fichier média dans le slide
    $(document).on('click', '.slider-remove-media', function() {
        const slideIndex = $(this).data('slide-index');
        const $mediaContainer = $(this).closest('.media-file-field');

        // Cache le bouton de suppression et l'élément média
        $mediaContainer.find('input[type="hidden"]').remove();
        $mediaContainer.find('img, video').remove();
        $(this).remove();

        // Ajouter un champ caché pour indiquer que le média doit être supprimé
        $mediaContainer.append(`<input type="hidden" name="builder_blocks[${slideIndex}][data][slides][${slideIndex}][media_file]" value="">`);
    });


    // Initialiser l'affichage des champs de média en fonction de la sélection actuelle
    $('.media-type-select').each(function() {
        $(this).trigger('change');
    });   
}