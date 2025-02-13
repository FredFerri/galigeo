(function ($) {
    $(document).ready(function () {
        function toggleBackgroundFields(column) {
            const bgType = column.find('.bg-type-select').val();
            column.find('.bg-color-field').toggleClass('hidden', bgType !== 'color');
            column.find('.bg-image-field').toggleClass('hidden', bgType !== 'image');
        }

        function handleMediaFrame(buttonClass, inputClass, previewClass, containerClass, removeClass) {
            $(document).on('click', buttonClass, function (event) {
                event.preventDefault();

                const button = $(this);
                const column = button.closest('.simple-columns-field');
                const input = column.find(inputClass);
                const preview = column.find(previewClass);
                const container = column.find(containerClass);

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

                const column = $(this).closest('.simple-columns-field');
                column.find(inputClass).val('');
                column.find(previewClass).attr('src', '');
                column.find(containerClass).addClass('hidden');
            });
        }

        $('.simple-columns-field').each(function () {
            const column = $(this);
            toggleBackgroundFields(column);
        });

        $(document).on('change', '.simple-columns-block .bg-type-select', function () {
            const column = $(this).closest('.simple-columns-field');
            toggleBackgroundFields(column);
        });

        handleMediaFrame(
            '.simple-columns-extra-image-selector',
            '.simple-columns-extra-image-url',
            '.simple-columns-extra-image-preview',
            '.simple-columns-extra-image-container',
            '.simple-columns-remove-extra-image'
        );

        handleMediaFrame(
            '.simple-columns-bg-image-selector',
            '.simple-columns-bg-image-url',
            '.simple-columns-bg-image-preview',
            '.simple-columns-bg-image-container',
            '.simple-columns-remove-bg-image'
        );
    });
})(jQuery);
