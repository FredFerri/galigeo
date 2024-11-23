function admin_text_block_scripts($, builderContainer) {

    jQuery(document).ready(function ($) {
        // Vérifier si un bloc "Texte" est présent sur la page
        if (jQuery('.text-block').length > 0) {
            $(document).on('click', '.add-text-button', function (e) {
                e.preventDefault();

                // Récupère l'index du bloc actuel depuis le bouton cliqué
                const blockIndex = $(this).data('block-index');
                
                // Sélectionne le container des boutons
                const buttonsContainer = $(`#text-buttons-container-${blockIndex}`);

                // Compte le nombre de boutons existants dans ce bloc pour déterminer l'index du prochain bouton
                const buttonCount = buttonsContainer.children('.button-fields').length;

                // Récupère le template HTML du bouton et remplace les placeholders
                let templateHtml = $(`#text-button-template-${blockIndex}`).html()
                    .replace(/BUTTON_INDEX/g, buttonCount)
                    .replace(/BLOCK_INDEX/g, blockIndex);

                // Ajoute le nouveau bouton au container
                buttonsContainer.append(templateHtml);

                // Mettre à jour les attributs ID, name et le titre des nouveaux champs
                buttonsContainer.children('.button-fields').each(function (index) {
                    $(this).find('input, select, textarea').each(function () {
                        const name = $(this).attr('name');
                        if (name) {
                            $(this).attr('name', name.replace(/\[buttons\]\[\d+\]/, `[buttons][${index}]`));
                        }
                        const id = $(this).attr('id');
                        if (id) {
                            $(this).attr('id', id.replace(/buttons-\d+/, `buttons-${index}`));
                        }
                    });

                    // Mettre à jour le titre du bouton
                    $(this).find('h4 .button-number').text(index + 1);
                });

                console.log(`Added button ${buttonCount} to block ${blockIndex}`);
            });

            // Gérer la suppression de bouton
            $(document).on('click', '.remove-button', function (e) {
                e.preventDefault();
                $(this).closest('.button-fields').remove();

                // Mettre à jour les index des boutons après suppression
                const blockIndex = $(this).closest('.builder-block').data('index');
                const buttonsContainer = $(`#text-buttons-container-${blockIndex}`);
                buttonsContainer.children('.button-fields').each(function (index) {
                    // Mettre à jour l'index de chaque bouton
                    $(this).find('input, select, textarea').each(function () {
                        const name = $(this).attr('name');
                        if (name) {
                            $(this).attr('name', name.replace(/\[buttons\]\[\d+\]/, `[buttons][${index}]`));
                        }
                        const id = $(this).attr('id');
                        if (id) {
                            $(this).attr('id', id.replace(/buttons-\d+/, `buttons-${index}`));
                        }
                    });

                    // Mettre à jour le titre du bouton
                    $(this).find('h4 .button-number').text(index + 1);
                });

                console.log('Button removed and indexes updated');
            });
        }
    });


    function updateButtonIndexes(container) {
        container.children('.button-fields').each(function(index) {
            $(this).find('.button-number').text(index + 1);
            $(this).find('input, select').each(function() {
                const name = $(this).attr('name');
                if (name) {
                    // Remplacer BUTTON_INDEX par l'index correct
                    $(this).attr('name', name.replace(/\[buttons\]\[\d+\]/, `[buttons][${index}]`));
                }
            });
        });
    }


    // Gestion de la suppression de bouton
    $(document).on('click', '.remove-text-button', function(e) {
        e.preventDefault();
        const buttonField = $(this).closest('.text-button-fields');
        const buttonsContainer = buttonField.parent();
        const addButton = buttonsContainer.siblings('.add-text-button');

        buttonField.remove();
        addButton.show();

        updateTextButtonsIndexes(buttonsContainer);
    });


    // Gestion du type de background pour le block texte
    builderContainer.on('change', '.bg-type-select', function() {
        const bgType = $(this).val();
        const block = $(this).closest('.builder-block');
        block.find('.bg-color-field').toggle(bgType === 'color');
        block.find('.bg-image-field').toggle(bgType === 'image');
    });

}

