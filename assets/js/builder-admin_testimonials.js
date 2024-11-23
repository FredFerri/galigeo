function admin_testimonials_scripts($, builderContainer) {
    // Fonction pour ajouter un témoignage
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

    // Fonction pour supprimer un témoignage
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

    // Gestion du type de background (image/couleur)
    builderContainer.on('change', '.testimonials-block .bg-type-select', function () {
        const bgType = $(this).val();

        const testimonialField = $(this).closest('.testimonial-fields');
        testimonialField.find('.bg-color-field').toggle(bgType === 'color');
        testimonialField.find('.bg-image-field').toggle(bgType === 'image');
    });

    // Gestion de l'upload et de la suppression des images via WP Media
    function handleMediaFrame(buttonClass, inputClass, previewClass, containerClass, removeClass) {
        builderContainer.on('click', buttonClass, function (event) {
            event.preventDefault();

            const button = $(this);
            const testimonial = button.closest('.testimonial-fields');
            const input = testimonial.find(inputClass);
            const preview = testimonial.find(previewClass);
            const container = testimonial.find(containerClass);

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

        builderContainer.on('click', removeClass, function (event) {
            event.preventDefault();

            const testimonial = $(this).closest('.testimonial-fields');
            testimonial.find(inputClass).val('');
            testimonial.find(previewClass).attr('src', '');
            testimonial.find(containerClass).addClass('hidden');
        });
    }

    // Initialisation de l'upload des images
    handleMediaFrame(
        '.extra-image-selector', // Bouton pour ouvrir WP Media
        '.extra-image-url', // Input caché pour l'URL
        '.extra-image-preview', // Aperçu de l'image
        '.extra-image-container', // Container de l'aperçu
        '.remove-extra-image' // Bouton pour supprimer l'image
    );
}
