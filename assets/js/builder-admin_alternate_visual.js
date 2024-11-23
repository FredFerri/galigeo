function admin_alternate_visual_scripts($, builderContainer) {    
    // Gestion du type de background pour le CTA
    builderContainer.on('change', '.av_background_type_selector', function() {
        const bgType = $(this).val();
        const ctaBlock = $(this).closest('.builder-block');
        ctaBlock.find('.av_bg-color-field').toggle(bgType === 'color');
        ctaBlock.find('.av_bg-image-field').toggle(bgType === 'image');
    });

    builderContainer.on('change', '.av_show_button_checkbox', function() {
        const showBtnVal = $(this).val();
        $(this).parent().parent().next('.av_button-options').toggleClass('hidden');

    });    

    /* Gestion de la suppression dynamique des images */
    const removeImgButtons = document.querySelectorAll('.alt-remove-img');

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

(function ($) {
    $(document).ready(function () {
        // Fonction pour basculer les champs entre couleur et image
        function toggleBackgroundFields(block) {
            const bgType = block.find('.av-background-type-select').val();
            const colorField = block.find('.av-bg-color-field');
            const imageField = block.find('.av-bg-image-field');

            if (bgType === 'image') {
                colorField.addClass('hidden');
                imageField.removeClass('hidden');
            } else {
                colorField.removeClass('hidden');
                imageField.addClass('hidden');
            }
        }

        // Gestion du changement de type de background
        $(document).on('change', '.av-background-type-select', function () {
            const block = $(this).closest('.alternate-visual-block');
            toggleBackgroundFields(block);
        });

        // Gestion de l'affichage des options du bouton
        $(document).on('change', '.av_show_button_checkbox', function () {
            const block = $(this).closest('.alternate-visual-block');
            const buttonOptions = block.find('.av_button-options');
            if ($(this).is(':checked')) {
                buttonOptions.removeClass('hidden').addClass('block');
            } else {
                buttonOptions.addClass('hidden').removeClass('block');
            }
        });

        // Fonction générique pour gérer les sélecteurs d'image
        function handleMediaFrame(buttonClass, urlClass, previewClass, containerClass, removeButtonClass) {
            $(document).on('click', buttonClass, function (event) {
                event.preventDefault();

                const button = $(this);
                const block = button.closest('.alternate-visual-block');

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

                const block = $(this).closest('.alternate-visual-block');
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
            '.av-inner-image-selector',
            '.av-inner-image-url',
            '.av-inner-image-preview',
            '.av-inner-image-container',
            '.remove-av-inner-image'
        );

        handleMediaFrame(
            '.av-bg-image-selector',
            '.av-bg-image-url',
            '.av-bg-image-preview',
            '.av-bg-image-container',
            '.remove-av-bg-image'
        );

        // Initialisation des champs au chargement
        $('.alternate-visual-block').each(function () {
            const block = $(this);
            toggleBackgroundFields(block);
        });
    });
})(jQuery);


}    

