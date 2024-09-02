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

        const blockIndex = sliderContainer.data('block-index');
        if (blockIndex === undefined) {
            console.error("Block index not found on slider container.");
            return;
        }

        const slideCount = sliderContainer.children().length;
        console.dir(sliderContainer);
        alert(slideCount);
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
}