function admin_cta_scripts($) {
    // Gestion de la suppression de bouton CTA
    $(document).on("click", ".cta-block .remove-cta-button", function (e) {
        e.preventDefault();
        const buttonField = $(this).closest(".cta-button-fields");
        const buttonsContainer = buttonField.parent();
        const addButton = buttonsContainer.siblings(".add-cta-button");

        buttonField.remove();
        addButton.show();

        // Mettre à jour les indices des boutons restants
        buttonsContainer.children().each(function (index) {
            $(this)
                .find("input, select")
                .each(function () {
                    const name = $(this).attr("name");
                    if (name) {
                        $(this).attr(
                            "name",
                            name.replace(/\[buttons\]\[\d+\]/, `[buttons][${index}]`)
                        );
                    }
                });
            $(this).find("h4").text(`Bouton ${index + 1}`);
        });
    });

    // Gestion du type de background pour le CTA
    $(document).on("change", ".cta-block .bg-type-select", function () {
        const bgType = $(this).val();
        const ctaBlock = $(this).closest(".cta-block");

        console.log("bgType:", bgType); // Vérifions la valeur sélectionnée
        console.log("ctaBlock trouvé:", ctaBlock.length > 0);

        if (ctaBlock.length) {
            const bgColorField = ctaBlock.find(".bg-color-field");
            const bgImageField = ctaBlock.find(".bg-image-field");

            console.log("bgColorField trouvé:", bgColorField.length > 0);
            console.log("bgImageField trouvé:", bgImageField.length > 0);

            if (bgType === "color") {
                bgColorField.removeClass("hidden");
                bgImageField.addClass("hidden");
            } else if (bgType === "image") {
                bgColorField.addClass("hidden");
                bgImageField.removeClass("hidden");
            }
        } else {
            console.error("ctaBlock not found!");
        }
    });

    // Gestion de l'ajout de bouton CTA
    $(document).on("click", ".cta-block .add-cta-button", function (e) {
        e.preventDefault();
        const blockIndex = $(this).data("block-index");
        const buttonsContainer = $(`#cta-buttons-container-${blockIndex}`);
        const buttonCount = buttonsContainer.children().length;

        if (buttonCount < 2) {
            const templateId = `cta-button-template-${blockIndex}`;
            const template = document.getElementById(templateId);

            if (template) {
                let newButtonHtml = template.innerHTML.replace(
                    /BUTTON_INDEX/g,
                    buttonCount
                );
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
        container.children(".cta-button-fields").each(function (index) {
            $(this).find("h4").text(`Bouton ${index + 1}`);
            $(this)
                .find("input, select")
                .each(function () {
                    const name = $(this).attr("name");
                    if (name) {
                        $(this).attr(
                            "name",
                            name.replace(/\[buttons\]\[\d+\]/, `[buttons][${index}]`)
                        );
                    }
                });

            // Ajouter ou supprimer le bouton de suppression
            if (index > 0) {
                if (!$(this).find(".remove-cta-button").length) {
                    $(this).append(
                        '<button type="button" class="remove-cta-button mt-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Supprimer ce bouton</button>'
                    );
                }
            } else {
                $(this).find(".remove-cta-button").remove();
            }
        });
    }

    let mediaFrame = null;

    // Initialisation des événements pour chaque bloc
    $(".cta-block").each(function () {
        const block = $(this);

        const bgTypeSelect = block.find(".cta-bg-type-select");
        const bgImageField = block.find(".cta-bg-image-field");
        const bgImageInput = block.find(".cta-bg-image-url");
        const bgImageContainer = block.find(".cta-bg-image-container");
        const bgImagePreview = block.find(".cta-bg-image-preview");
        const imageSelectorButton = block.find(".cta-image-selector-button");
        const removeImageButton = block.find(".remove-cta-bg-image");

        // Basculer entre champs d'image et de couleur
        function toggleBackgroundFields() {
            console.log("toggleBackgroundFields appelé.");
            if (bgTypeSelect.val() === "image") {
                bgImageField.removeClass("hidden");
                bgImageContainer.removeClass("hidden");
            } else {
                bgImageField.addClass("hidden");
                resetImageField(); // Réinitialiser l'image si on bascule sur "couleur"
            }
        }

        // Ouvrir le modal pour sélectionner ou uploader une image
        imageSelectorButton.off("click").on("click", function (event) {
            event.preventDefault();

            if (!mediaFrame) {
                mediaFrame = wp.media({
                    title: "Choisir une image",
                    button: { text: "Utiliser cette image" },
                    multiple: false,
                });

                mediaFrame.on("select", function () {
                    const attachment = mediaFrame.state().get("selection").first().toJSON();
                    setImage(attachment.url);
                });
            }

            mediaFrame.open();
        });

        function setImage(imageUrl) {
            console.log("Image sélectionnée:", imageUrl);
            bgImageInput.val(imageUrl);
            bgImagePreview.attr("src", imageUrl);
            bgImageContainer.removeClass("hidden");
            removeImageButton.removeClass("hidden");
        }

        removeImageButton.off("click").on("click", function (event) {
            event.preventDefault();
            resetImageField();
        });

        function resetImageField() {
            console.log("Réinitialisation du champ image.");
            bgImageInput.val("");
            bgImagePreview.attr("src", "");
            bgImageContainer.addClass("hidden");
            removeImageButton.addClass("hidden");
        }

        toggleBackgroundFields();
        bgTypeSelect.on("change", toggleBackgroundFields);
    });

(function ($) {
    $(document).ready(function () {
        // Fonction pour basculer les champs entre couleur et image
        function toggleBackgroundFields(block) {
            const bgType = block.find('.cta-bg-type-select').val();
            const colorField = block.find('.cta-bg-color-field');
            const imageField = block.find('.cta-bg-image-field');

            if (bgType === 'image') {
                colorField.addClass('hidden');
                imageField.removeClass('hidden');
            } else {
                colorField.removeClass('hidden');
                imageField.addClass('hidden');
            }
        }

        // Gestion du changement de type de background
        $(document).on('change', '.cta-bg-type-select', function () {
            const block = $(this).closest('.cta-block');
            toggleBackgroundFields(block);
        });

        // Fonction générique pour gérer les sélecteurs d'image
        function handleMediaFrame(buttonClass, urlClass, previewClass, containerClass, removeButtonClass) {
            $(document).on('click', buttonClass, function (event) {
                event.preventDefault();

                const button = $(this);
                const block = button.closest('.cta-block');

                if (!block.length) {
                    console.error('Bloc parent introuvable.');
                    return;
                }

                const imageUrlInput = block.find(urlClass);
                const imagePreview = block.find(previewClass);
                const imageContainer = block.find(containerClass);
                const removeImageButton = block.find(removeButtonClass);

                const mediaFrame = wp.media({
                    title: 'Choisir une image',
                    button: { text: 'Utiliser cette image' },
                    multiple: false,
                });

                mediaFrame.on('select', function () {
                    const attachment = mediaFrame.state().get('selection').first().toJSON();
                    imageUrlInput.val(attachment.url);
                    imagePreview.attr('src', attachment.url);
                    imageContainer.removeClass('hidden');
                    removeImageButton.removeClass('hidden');
                });

                mediaFrame.open();
            });

            // Gestion du bouton "Supprimer"
            $(document).on('click', removeButtonClass, function (event) {
                event.preventDefault();

                const block = $(this).closest('.cta-block');
                const imageUrlInput = block.find(urlClass);
                const imagePreview = block.find(previewClass);
                const imageContainer = block.find(containerClass);

                imageUrlInput.val('');
                imagePreview.attr('src', '');
                imageContainer.addClass('hidden');
            });
        }

        // Initialisation pour chaque champ d'image
        handleMediaFrame(
            '.cta-inner-image-selector',
            '.cta-inner-image-url',
            '.cta-inner-image-preview',
            '.cta-inner-image-container',
            '.remove-cta-inner-image'
        );

        handleMediaFrame(
            '.cta-bg-image-selector',
            '.cta-bg-image-url',
            '.cta-bg-image-preview',
            '.cta-bg-image-container',
            '.remove-cta-bg-image'
        );

        // Initialisation des champs au chargement
        $('.cta-block').each(function () {
            const block = $(this);
            toggleBackgroundFields(block);
        });
    });
})(jQuery);


}

