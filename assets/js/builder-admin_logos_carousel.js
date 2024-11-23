(function ($) {
    $(document).ready(function () {
        // Fonction générique pour gérer les uploads
        function handleMediaFrame(buttonClass, inputClass, previewClass, containerClass, removeClass, multiple = false) {
            $(document).on('click', buttonClass, function (event) {
                event.preventDefault();

                const button = $(this);
                const block = button.closest('.client-carousel-block');
                const input = block.find(inputClass);
                const preview = block.find(previewClass);
                const container = block.find(containerClass);

                // Ouvrir le Media Frame WordPress
                const mediaFrame = wp.media({
                    title: 'Choisir une image',
                    button: { text: 'Utiliser cette image' },
                    multiple: multiple, // Gérer le mode multiple
                });

                // Lorsqu'une image est sélectionnée
                mediaFrame.on('select', function () {
                    const selection = mediaFrame.state().get('selection');

                    if (multiple) {
                        // Gestion des logos multiples
                        selection.each(function (attachment) {
                            attachment = attachment.toJSON();
                            const logoContainer = `
                                <div class="relative lc-logo-item">
                                    <img src="${attachment.url}" alt="Logo" class="max-w-full h-auto">
                                    <button type="button" class="absolute top-0 right-0 bg-red-500 text-white p-1 rounded-full lc-remove-logo">
                                        &times;
                                    </button>
                                    <input type="hidden" name="${input.data('name')}[]" value="${attachment.url}">
                                </div>`;
                            container.append(logoContainer);
                        });
                    } else {
                        // Gestion d'une seule image (background)
                        const attachment = selection.first().toJSON();
                        input.val(attachment.url);
                        preview.attr('src', attachment.url);
                        container.removeClass('hidden');
                    }
                });

                mediaFrame.open();
            });

            // Gestion du bouton "Supprimer"
            $(document).on('click', removeClass, function (event) {
                event.preventDefault();

                const block = $(this).closest('.client-carousel-block');
                const input = block.find(inputClass);
                const preview = block.find(previewClass);
                const container = block.find(containerClass);

                if (multiple) {
                    $(this).closest('.lc-logo-item').remove(); // Supprime uniquement le logo spécifique
                } else {
                    input.val('');
                    preview.attr('src', '');
                    container.addClass('hidden');
                }
            });
        }

        // Initialisation pour le champ "Logos multiples"
        handleMediaFrame(
            '.lc-logo-selector',        // Bouton
            '.lc-logo-input',           // Input caché
            '',                         // Pas d'aperçu unique
            '.lc-logos-container',      // Conteneur des logos
            '.lc-remove-logo',          // Bouton de suppression
            true                        // Mode multiple
        );

        // Initialisation pour le champ "Image de background"
        handleMediaFrame(
            '.lc-bg-image-selector',    // Bouton
            '.lc-bg-image-url',         // Input caché
            '.lc-bg-image-preview',     // Aperçu
            '.lc-bg-image-container',   // Conteneur de l'image
            '.lc-remove-bg-image',      // Bouton de suppression
            false                       // Mode unique
        );

        // Gestion du type de background (couleur ou image)
        $(document).on('change', '.lc_background_type_selector', function () {
            const bgType = $(this).val();
            const block = $(this).closest('.client-carousel-block');
            block.find('.lc_bg_color_field').toggleClass('hidden', bgType !== 'color');
            block.find('.lc_bg_image_field').toggleClass('hidden', bgType !== 'image');
        });
    });
})(jQuery);
