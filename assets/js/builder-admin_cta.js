function admin_cta_scripts($, builderContainer) {

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
}