(function ($) {
    $(document).ready(function () {
        console.log("Media handler script chargé avec succès.");

        let mediaFrame = null;

        $(document).on('click', '.image-selector-button', function (event) {
            event.preventDefault();

            const button = $(this);
            const block = button.closest('.builder-block');
            const imageUrlInput = block.find('.image-url-input');
            const imagePreview = block.find('.image-preview');
            const removeImageButton = block.find('.remove-image-button');

            if (!mediaFrame) {
                mediaFrame = wp.media({
                    title: 'Choisir une image',
                    button: { text: 'Utiliser cette image' },
                    multiple: false,
                });

                mediaFrame.on('select', function () {
                    const attachment = mediaFrame.state().get('selection').first().toJSON();
                    imageUrlInput.val(attachment.url);
                    imagePreview.attr('src', attachment.url).removeClass('hidden');
                    removeImageButton.removeClass('hidden');
                });
            }

            mediaFrame.open();
        });

        $(document).on('click', '.remove-image-button', function (event) {
            event.preventDefault();

            const button = $(this);
            const block = button.closest('.builder-block');
            const imageUrlInput = block.find('.image-url-input');
            const imagePreview = block.find('.image-preview');

            imageUrlInput.val('');
            imagePreview.attr('src', '').addClass('hidden');
            button.addClass('hidden');
        });
    });
})(jQuery);
