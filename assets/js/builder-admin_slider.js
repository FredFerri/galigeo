function admin_slider_scripts($, builderContainer) {
    // Gestion de l'ajout de slide avec débogage
    builderContainer.on('click', '.slider-block .add-slide', function(e) {
        e.preventDefault();

        const sliderContainer = $(this).siblings('.slider-block .slider-slides');
        if (!sliderContainer.length) {
            console.error("Slider container (.slider-slides) not found.");
            return;
        }

        const blockIndex = sliderContainer.closest('.builder-block').data('index');
        if (blockIndex === undefined) {
            console.error("Block index not found on slider container.");
            return;
        }

        const slideCount = sliderContainer.children().length;
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
    builderContainer.on('change', '.slider-block .bg-type-select', function() {
        const bgType = $(this).val();
        const slideField = $(this).closest('.slide-fields');
        slideField.find('.bg-color-field').toggle(bgType === 'color');
        slideField.find('.bg-image-field').toggle(bgType === 'image');
        slideField.find('.bg-color-field').toggleClass('hidden', bgType !== 'color');
        slideField.find('.bg-image-field').toggleClass('hidden', bgType !== 'image');        
    });


// Gestion des modals pour les champs d'image
        function handleMediaFrame(buttonClass, inputClass, previewClass, containerClass, removeClass) {
            $(document).on('click', buttonClass, function (event) {
                event.preventDefault();

                const button = $(this);
                const slide = button.closest('.slide-fields');
                const input = slide.find(inputClass);
                const preview = slide.find(previewClass);
                const container = slide.find(containerClass);
                const removeButton = slide.find(removeClass);

                const mediaFrame = wp.media({
                    title: 'Choisir une image',
                    button: { text: 'Utiliser cette image' },
                    multiple: false,
                });

                mediaFrame.on('select', function () {
                    const attachment = mediaFrame.state().get('selection').first().toJSON();
                    input.val(attachment.url);
                    preview.attr('src', attachment.url).removeClass('hidden');
                    container.removeClass('hidden');
                    removeButton.removeClass('hidden');
                });

                mediaFrame.open();
            });

            $(document).on('click', removeClass, function (event) {
                event.preventDefault();

                const slide = $(this).closest('.slide-fields');
                slide.find(inputClass).val('');
                slide.find(previewClass).attr('src', '').addClass('hidden');
                slide.find(containerClass).addClass('hidden');
            });
        }

        // Initialisation de l'upload d'image de background
        handleMediaFrame(
            '.slide-bg-image-selector',
            '.slide-bg-image-url',
            '.slide-bg-image-preview',
            '.slide-bg-image-container',
            '.slide-remove-bg-image'
        ); 

        // Initialisation de l'upload de l'image supplémentaire
        handleMediaFrame(
            '.slide-image-selector',
            '.slide-image-url',
            '.slide-image-preview',
            '.slide-image-container',
            '.slide-remove-image'
        );         

    // Gérer l'affichage/masquage des options du bouton
    jQuery(document).on('change', '.slider-block .show_button_checkbox', function() {
        var index = jQuery(this).data('index');
        var buttonOptions = jQuery('.builder-block[data-type="slider"] .button-options[data-index="' + index + '"]');
        if (this.checked) {
            buttonOptions.removeClass('hidden').addClass('block');
        } else {
            buttonOptions.removeClass('block').addClass('hidden');
        }
    });    
}