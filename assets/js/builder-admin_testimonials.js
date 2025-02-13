function admin_testimonials_scripts($, builderContainer) {
    // Ajouter un témoignage
    builderContainer.on('click', '.add-testimonial', function (e) {
        e.preventDefault();

        const testimonialContainer = $(this).siblings('.testimonials-slides');

        if (!testimonialContainer.length) {
            console.error("Le container des témoignages n'a pas été trouvé.");
            return;
        }

        const blockIndex = testimonialContainer.closest('.builder-block').data('index');
        const testimonialCount = testimonialContainer.children('.testimonial-fields').length;

        if (testimonialCount < 6) {
            const newTestimonial = $('#testimonial-template').html()
                .replace(/BLOCK_INDEX/g, blockIndex)
                .replace(/TESTIMONIAL_INDEX/g, testimonialCount);

            testimonialContainer.append(newTestimonial);

            updateTestimonialNumbers(testimonialContainer);

            if (testimonialCount >= 5) {
                $(this).prop('disabled', true); // Désactiver le bouton si on atteint le maximum
            }
        } else {
            console.warn("Limite de témoignages atteinte.");
        }
    });

    // Supprimer un témoignage
    builderContainer.on('click', '.remove-testimonial', function (e) {
        e.preventDefault();

        const testimonialContainer = $(this).closest('.testimonials-slides');

        $(this).closest('.testimonial-fields').remove();

        updateTestimonialNumbers(testimonialContainer);

        if (testimonialContainer.children('.testimonial-fields').length < 6) {
            testimonialContainer.siblings('.add-testimonial').prop('disabled', false); // Réactiver le bouton si on est en dessous du maximum
        }
    });

    // Met à jour les indices après ajout/suppression de témoignages
    function updateTestimonialNumbers(testimonialContainer) {
        testimonialContainer.children('.testimonial-fields').each(function (index) {
            $(this).find('.testimonial-number').text(index + 1);
            $(this).find('input, select, textarea').each(function () {
                const name = $(this).attr('name');

                if (name) {
                    const newName = name.replace(/\[testimonials\]\[\d+\]/, '[testimonials][' + index + ']');
                    $(this).attr('name', newName);
                }
            });
        });
    }

    // Gestion de l'upload et de la suppression des images via WP Media
    function handleMediaFrame(buttonClass, inputClass, previewClass, containerClass, removeClass) {
        builderContainer.on('click', buttonClass, function (event) {
            event.preventDefault();

            const button = $(this);
            const container = button.closest('.testimonial-fields');
            const input = container.find(inputClass);
            const preview = container.find(previewClass);
            const previewContainer = container.find(containerClass);

            const mediaFrame = wp.media({
                title: 'Choisir une image',
                button: { text: 'Utiliser cette image' },
                multiple: false,
            });

            mediaFrame.on('select', function () {
                const attachment = mediaFrame.state().get('selection').first().toJSON();
                input.val(attachment.url);
                preview.attr('src', attachment.url);
                previewContainer.removeClass('hidden');
            });

            mediaFrame.open();
        });

        builderContainer.on('click', removeClass, function (event) {
            event.preventDefault();

            const container = $(this).closest('.testimonial-fields');
            container.find(inputClass).val('');
            container.find(previewClass).attr('src', '');
            container.find(containerClass).addClass('hidden');
        });
    }

    // Gestion de l'upload et de la suppression des images via WP Media
    function handleBackgroundMediaFrame(buttonClass, inputClass, previewClass, containerClass, removeClass) {
        builderContainer.on('click', buttonClass, function (event) {
            event.preventDefault();

            const button = $(this);
            const container = button.closest('.testimonials-top');
            const input = container.find(inputClass);
            const preview = container.find(previewClass);
            const previewContainer = container.find(containerClass);

            const mediaFrame = wp.media({
                title: 'Choisir une image',
                button: { text: 'Utiliser cette image' },
                multiple: false,
            });

            mediaFrame.on('select', function () {
                const attachment = mediaFrame.state().get('selection').first().toJSON();
                input.val(attachment.url);
                preview.attr('src', attachment.url);
                previewContainer.removeClass('hidden');
            });

            mediaFrame.open();
        });

        builderContainer.on('click', removeClass, function (event) {
            event.preventDefault();

            const container = $(this).closest('.testimonials-top');
            container.find(inputClass).val('');
            container.find(previewClass).attr('src', '');
            container.find(containerClass).addClass('hidden');
        });
    }    

    // Initialisation de l'upload des images des témoignages
    handleMediaFrame(
        '.testimonial-extra-image-selector', // Bouton pour ouvrir WP Media
        '.testimonial-extra-image-url', // Input caché pour l'URL
        '.testimonial-extra-image-preview', // Aperçu de l'image
        '.testimonial-extra-image-container', // Container de l'aperçu
        '.testimonial-remove-extra-image' // Bouton pour supprimer l'image
    );

    // Initialisation de l'upload pour l'image de background
    handleBackgroundMediaFrame(
        '.testimonial-background-image-selector', // Bouton pour ouvrir WP Media
        '.testimonial-background-image-url', // Input caché pour l'URL
        '.testimonial-background-image-preview', // Aperçu de l'image
        '.testimonial-background-image-container', // Container de l'aperçu
        '.testimonial-remove-background-image' // Bouton pour supprimer l'image
    );
}
