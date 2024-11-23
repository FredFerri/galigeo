(function ($) {
    $(document).ready(function () {
        // Gestion du type de background
        function toggleBackgroundFields(slide) {
            const bgType = slide.find('.bg-type-select').val();
            slide.find('.bg-color-field').toggleClass('hidden', bgType !== 'color');
            slide.find('.bg-image-field').toggleClass('hidden', bgType !== 'image');
        }

        // Gestion de l'affichage des options de bouton
        function toggleButtonOptions(slide) {
            const checkbox = slide.find('input[name$="[show_button]"]');
            const buttonOptions = slide.find('.button-options');
            buttonOptions.toggleClass('hidden', !checkbox.is(':checked'));
        }

        // Réindexation des slides après ajout/suppression
        function updateSlideIndices(sliderContainer) {
            sliderContainer.children('.slide-home-fields').each(function (index) {
                const slide = $(this);

                // Met à jour le numéro affiché
                slide.find('.slide-home-number').text(index + 1);

                // Met à jour les noms des champs
                slide.find('input, select, textarea').each(function () {
                    const name = $(this).attr('name');
                    if (name) {
                        $(this).attr('name', name.replace(/\[slides\]\[\d+\]/, `[slides][${index}]`));
                    }
                });
            });
        }

        // Gestion des modals pour les champs d'image
        function handleMediaFrame(buttonClass, inputClass, previewClass, containerClass, removeClass) {
            $(document).on('click', buttonClass, function (event) {
                event.preventDefault();

                const button = $(this);
                const slide = button.closest('.slide-home-fields');
                const input = slide.find(inputClass);
                const preview = slide.find(previewClass);
                const container = slide.find(containerClass);
                const removeButton = slide.find(removeClass);

                const mediaFrame = wp.media({
                    title: 'Choisir une image',
                    button: { text: 'Utiliser cette image' },
                    multiple: false,
                });

                mediaFrame.on('select', function () {
                    const attachment = mediaFrame.state().get('selection').first().toJSON();
                    input.val(attachment.url);
                    preview.attr('src', attachment.url).removeClass('hidden');
                    container.removeClass('hidden');
                    removeButton.removeClass('hidden');
                });

                mediaFrame.open();
            });

            $(document).on('click', removeClass, function (event) {
                event.preventDefault();

                const slide = $(this).closest('.slide-home-fields');
                slide.find(inputClass).val('');
                slide.find(previewClass).attr('src', '').addClass('hidden');
                slide.find(containerClass).addClass('hidden');
            });
        }

        // Ajout dynamique de slides
        $(document).on('click', '.add-slide-home', function (event) {
            event.preventDefault();

            const sliderContainer = $(this).siblings('.slider-home-slides');
            const slideCount = sliderContainer.children('.slide-home-fields').length;

            if (slideCount < 4) {
                const templateSlide = sliderContainer.children('.slide-home-fields').first();
                const newSlide = templateSlide.clone();

                // Réinitialiser les valeurs du nouveau slide
                newSlide.find('input, select, textarea').each(function () {
                    const input = $(this);
                    input.val(''); // Réinitialise la valeur
                    if (input.is(':checkbox')) {
                        input.prop('checked', false); // Réinitialise les checkbox
                    }
                });

                newSlide.find('.bg-image-preview').attr('src', '').addClass('hidden');
                newSlide.find('.bg-image-container').addClass('hidden');
                newSlide.find('.extra-image-preview').attr('src', '').addClass('hidden');
                newSlide.find('.extra-image-container').addClass('hidden');

                sliderContainer.append(newSlide);

                // Mettre à jour les indices
                updateSlideIndices(sliderContainer);

                // Initialiser les nouveaux champs
                toggleBackgroundFields(newSlide);
                toggleButtonOptions(newSlide);
            }
        });

        // Vérifie si une image est déjà présente pour chaque slide
        function initializeImageFields(slide) {
            slide.find('.bg-image-url').each(function () {
                const input = $(this);
                const preview = slide.find('.bg-image-preview');
                const container = slide.find('.bg-image-container');

                if (input.val()) {
                    preview.attr('src', input.val()).removeClass('hidden');
                    container.removeClass('hidden');
                }
            });

            slide.find('.extra-image-url').each(function () {
                const input = $(this);
                const preview = slide.find('.extra-image-preview');
                const container = slide.find('.extra-image-container');

                if (input.val()) {
                    preview.attr('src', input.val()).removeClass('hidden');
                    container.removeClass('hidden');
                }
            });
        }

        // Suppression dynamique de slides
        $(document).on('click', '.remove-slide-home', function (event) {
            event.preventDefault();

            const slide = $(this).closest('.slide-home-fields');
            const sliderContainer = slide.parent();
            slide.remove();

            // Mettre à jour les indices après suppression
            updateSlideIndices(sliderContainer);
        });

        // Gestion des événements
        $(document).on('change', '.bg-type-select', function () {
            const slide = $(this).closest('.slide-home-fields');
            console.dir(slide);
            toggleBackgroundFields(slide);
        });

        $(document).on('change', 'input[name$="[show_button]"]', function () {
            const slide = $(this).closest('.slide-home-fields');
            toggleButtonOptions(slide);
        });

        // Gestion des modals pour chaque type d'image
        handleMediaFrame(
            '.bg-image-selector',
            '.bg-image-url',
            '.bg-image-preview',
            '.bg-image-container',
            '.remove-bg-image'
        );

        handleMediaFrame(
            '.extra-image-selector',
            '.extra-image-url',
            '.extra-image-preview',
            '.extra-image-container',
            '.remove-extra-image'
        );

        // Initialisation lors du chargement de la page
        $('.slider-home-slides .slide-home-fields').each(function () {
            const slide = $(this);
            toggleBackgroundFields(slide);
            toggleButtonOptions(slide);
            initializeImageFields(slide);
        });
    });
})(jQuery);
