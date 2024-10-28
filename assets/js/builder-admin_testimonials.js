function admin_testimonials_scripts($, builderContainer) {

    // Fonction pour ajouter un témoignage
    builderContainer.on('click', '.add-testimonial', function(e) {
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
                $(this).prop('disabled', true);  // Désactiver le bouton si on atteint le maximum
            }
        } else {
            console.warn("Limite de témoignages atteinte.");
        }
    });

    // Fonction pour supprimer un témoignage
    builderContainer.on('click', '.remove-testimonial', function(e) {
        e.preventDefault();

        const testimonialContainer = $(this).closest('.testimonials-slides');

        $(this).closest('.testimonial-fields').remove();

        updateTestimonialNumbers(testimonialContainer);

        if (testimonialContainer.children('.testimonial-fields').length < 6) {
            testimonialContainer.siblings('.add-testimonial').prop('disabled', false);  // Réactiver le bouton si on est en dessous du maximum
        }
    });

    // Met à jour les indices après ajout/suppression de témoignages
    function updateTestimonialNumbers(testimonialContainer) {
        testimonialContainer.children('.testimonial-fields').each(function(index) {

            $(this).find('.testimonial-number').text(index + 1);
            $(this).find('input, select, textarea').each(function() {
                const name = $(this).attr('name');
                
                if (name) {
                    const newName = name.replace(/\[testimonials\]\[\d+\]/, '[testimonials][' + index + ']');
                    $(this).attr('name', newName);
                }
            });
        });
    }

    // Gestion du type de background (image/couleur)
    builderContainer.on('change', '.bg-type-select', function() {

        const bgType = $(this).val();

        const testimonialField = $(this).closest('.testimonial-fields');
        testimonialField.find('.bg-color-field').toggle(bgType === 'color');
        testimonialField.find('.bg-image-field').toggle(bgType === 'image');
    });
}
