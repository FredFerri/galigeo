jQuery(document).ready(function($) {
    const builderContainer = $('#builder-container');
    const blocksContainer = $('#builder-blocks');
    const addBlockButton = $('#add-block');
    const blockTemplates = $('#block-templates');

    let blockIndex = blocksContainer.children().length;

    // Gestion de l'ajout de block
    addBlockButton.on('click', function(e) {
        e.preventDefault();
        showBlockTypeDialog();
    });

    // Gestion de la suppression de block
    builderContainer.on('click', '.remove-block', function(e) {
        e.preventDefault();
        $(this).closest('.builder-block').remove();
        updateBlockIndexes();
    });


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
    builderContainer.on('click', '.remove-slide', function(e) {
        e.preventDefault();

        const sliderContainer = $(this).closest('.slider-slides');
        $(this).closest('.slide').remove();

        updateSlideButtons(sliderContainer);
        updateSlideNumbers(sliderContainer);
    });


    // Gestion de la suppression de slide
    builderContainer.on('click', '.remove-slide', function(e) {
        e.preventDefault();
        const slideContainer = $(this).closest('.slide-fields');
        const sliderContainer = slideContainer.parent();
        slideContainer.remove();
        updateSlideButtons(sliderContainer);
        updateSlideNumbers(sliderContainer);
    });

    function showBlockTypeDialog() {
        const dialog = $('<div>', {
            title: 'Choisir le type de block',
            html: '<p>Sélectionnez le type de block à ajouter :</p>' +
                  '<button class="button" data-type="slider">Slider</button> ' +
                  '<button class="button" data-type="video">Vidéo</button>' +
                  '<button class="button" data-type="call_to_action">Call to action</button>'
        }).dialog({
            modal: true,
            closeOnEscape: true,
            buttons: {
                "Annuler": function() {
                    $(this).dialog('close');
                }
            },
            create: function() {
                $(this).find('button[data-type]').on('click', function() {
                    const type = $(this).data('type');
                    addBlock(type);
                    dialog.dialog('close');
                });
            }
        });
    }

    function addBlock(type) {
        const template = blockTemplates.find(`.builder-block[data-type="${type}"]`).clone();
        template.attr('data-index', blockIndex);
        template.find('input, textarea, select').each(function() {
            const name = $(this).attr('name');
            if (name) {
                $(this).attr('name', name.replace('TEMPLATE_INDEX', blockIndex));
            }
        });
        blocksContainer.append(template);
        blockIndex++;
        updateBlockIndexes();
    }

    function updateBlockIndexes() {
        blocksContainer.children('.builder-block').each(function(index) {
            $(this).attr('data-index', index);
            $(this).find('input, textarea, select').each(function() {
                const name = $(this).attr('name');
                if (name) {
                    $(this).attr('name', name.replace(/\[(\d+)\]/, '[' + index + ']'));
                }
            });
        });
    }

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

    // CALL TO ACTION
    // Gestion de l'ajout de bouton CTA
    // builderContainer.on('click', '.add-cta-button', function(e) {
    //     e.preventDefault();
    //     const blockIndex = $(this).data('block-index');
    //     const buttonsContainer = $(`#cta-buttons-container-${blockIndex}`);
    //     const buttonCount = buttonsContainer.children().length;

    //     if (buttonCount < 2) {
    //         const newButton = $('#cta-button-template').html()
    //             .replace(/BLOCK_INDEX/g, blockIndex)
    //             .replace(/BUTTON_INDEX/g, buttonCount);
    //         buttonsContainer.append(newButton);

    //         if (buttonCount + 1 >= 2) {
    //             $(this).hide();
    //         }
    //     }
    // });

    // Gestion de la suppression de bouton CTA
    builderContainer.on('click', '.remove-cta-button', function(e) {
        e.preventDefault();
        const buttonField = $(this).closest('.cta-button-fields');
        const buttonsContainer = buttonField.parent();
        const addButton = buttonsContainer.siblings('.add-cta-button');

        buttonField.remove();
        addButton.show();

        // Mettre à jour les indices des boutons restants
        buttonsContainer.children().each(function(index) {
            $(this).find('input, select').each(function() {
                const name = $(this).attr('name');
                if (name) {
                    $(this).attr('name', name.replace(/\[buttons\]\[\d+\]/, `[buttons][${index}]`));
                }
            });
            $(this).find('h4').text(`Bouton ${index + 1}`);
        });
    });

    // Gestion du type de background pour le CTA
    builderContainer.on('change', '.bg-type-select', function() {
        const bgType = $(this).val();
        const ctaBlock = $(this).closest('.builder-block');
        ctaBlock.find('.bg-color-field').toggle(bgType === 'color');
        ctaBlock.find('.bg-image-field').toggle(bgType === 'image');
    });

    // Gestion de l'ajout de bouton CTA
    $(document).on('click', '.add-cta-button', function(e) {
        e.preventDefault();
        const blockIndex = $(this).data('block-index');
        const buttonsContainer = $(`#cta-buttons-container-${blockIndex}`);
        const buttonCount = buttonsContainer.children().length;

        if (buttonCount < 2) {
            const templateId = `cta-button-template-${blockIndex}`;
            const template = document.getElementById(templateId);
            
            if (template) {
                let newButtonHtml = template.innerHTML.replace(/BUTTON_INDEX/g, buttonCount);
                buttonsContainer.append(newButtonHtml);

                updateCTAButtonsIndexes(buttonsContainer);

                if (buttonCount + 1 >= 2) {
                    $(this).hide();
                }
            } else {
                console.error(`Template not found: ${templateId}`);
            }
        }
    });

    // Fonction mise à jour pour ajouter dynamiquement les boutons de suppression
    function updateCTAButtonsIndexes(container) {
        container.children('.cta-button-fields').each(function(index) {
            $(this).find('h4').text(`Bouton ${index + 1}`);
            $(this).find('input, select').each(function() {
                const name = $(this).attr('name');
                if (name) {
                    $(this).attr('name', name.replace(/\[buttons\]\[\d+\]/, `[buttons][${index}]`));
                }
            });

            // Ajouter ou supprimer le bouton de suppression
            if (index > 0) {
                if (!$(this).find('.remove-cta-button').length) {
                    $(this).append('<button type="button" class="remove-cta-button mt-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Supprimer ce bouton</button>');
                }
            } else {
                $(this).find('.remove-cta-button').remove();
            }
        });
    }

    // Gestion de la suppression de bouton CTA
    $(document).on('click', '.remove-cta-button', function(e) {
        e.preventDefault();
        const buttonField = $(this).closest('.cta-button-fields');
        const buttonsContainer = buttonField.parent();
        const addButton = buttonsContainer.siblings('.add-cta-button');

        buttonField.remove();
        addButton.show();

        updateCTAButtonsIndexes(buttonsContainer);
    });

});
