(function ($) {
    $(document).ready(function () {
        // Gestion du type de background
        $(document).on('change', '.heros-cas-client-block .bg-type-select', function () {
            const bgType = $(this).val();
            const block = $(this).closest('.heros-cas-client-block');
            block.find('.bg-color-field').toggleClass('hidden', bgType !== 'color');
            block.find('.bg-image-field').toggleClass('hidden', bgType !== 'image');
        });

        // Gestion de l'affichage des options du bouton
        $(document).on('change', '.hero-button-toggle', function () {
            const block = $(this).closest('.heros-cas-client-block');
            const buttonOptions = block.find('.hero-button-options');
            if ($(this).is(':checked')) {
                buttonOptions.removeClass('hidden').addClass('block');
            } else {
                buttonOptions.addClass('hidden').removeClass('block');
            }
        });        

        // Gestion des images via WP Media
        function handleMediaFrame(buttonClass, inputClass, previewClass, containerClass, removeClass) {
            $(document).on('click', buttonClass, function (event) {
                event.preventDefault();

                const button = $(this);
                const block = button.closest('.heros-cas-client-block');
                const input = block.find(inputClass);
                const preview = block.find(previewClass);
                const container = block.find(containerClass);

                const mediaFrame = wp.media({
                    title: 'Choisir une image',
                    button: { text: 'Utiliser cette image' },
                    multiple: false,
                });

                mediaFrame.on('select', function () {
                    const attachment = mediaFrame.state().get('selection').first().toJSON();
                    input.val(attachment.url);
                    preview.attr('src', attachment.url);
                    container.removeClass('hidden');
                });

                mediaFrame.open();
            });

            $(document).on('click', removeClass, function (event) {
                event.preventDefault();

                const block = $(this).closest('.heros-cas-client-block');
                block.find(inputClass).val('');
                block.find(previewClass).attr('src', '');
                block.find(containerClass).addClass('hidden');
            });
        }

        // Initialisation des images
        handleMediaFrame(
            '.hcc-main-image-selector',
            '.hcc-main-image-url',
            '.hcc-main-image-preview',
            '.hcc-main-image-container',
            '.hcc-remove-main-image'
        );

        handleMediaFrame(
            '.hcc-bg-image-selector',
            '.hcc-bg-image-url',
            '.hcc-bg-image-preview',
            '.hcc-bg-image-container',
            '.hcc-remove-bg-image'
        );
    });
})(jQuery);
