function admin_faq_scripts($, builderContainer) {
    // Fonction pour ajouter une FAQ
    builderContainer.on('click', '.add-faq', function (e) {
        e.preventDefault();

        const faqContainer = $(this).siblings('.faq-items');

        if (!faqContainer.length) {
            console.error("Le container des FAQs n'a pas été trouvé.");
            return;
        }

        const blockIndex = faqContainer.closest('.builder-block').data('index');
        const faqCount = faqContainer.children('.faq-fields').length;

        if (faqCount < 8) {
            const newFAQ = $('#faq-template').html()
                .replace(/BLOCK_INDEX/g, blockIndex)
                .replace(/FAQ_INDEX/g, faqCount);

            faqContainer.append(newFAQ);

            updateFAQNumbers(faqContainer);

            if (faqCount >= 7) {
                $(this).prop('disabled', true); // Désactiver le bouton si on atteint le maximum
            }
        } else {
            console.warn("Limite de FAQs atteinte.");
        }
    });

    // Fonction pour supprimer une FAQ
    builderContainer.on('click', '.remove-faq', function (e) {
        e.preventDefault();

        const faqContainer = $(this).closest('.faq-items');

        $(this).closest('.faq-fields').remove();

        updateFAQNumbers(faqContainer);

        if (faqContainer.children('.faq-fields').length < 8) {
            faqContainer.siblings('.add-faq').prop('disabled', false); // Réactiver le bouton si on est en dessous du maximum
        }
    });

    // Met à jour les indices et masque le bouton de suppression du premier élément
    function updateFAQNumbers(faqContainer) {
        faqContainer.children('.faq-fields').each(function (index) {
            $(this).find('.faq-number').text(index + 1);
            $(this).find('input, select, textarea').each(function () {
                const name = $(this).attr('name');
                if (name) {
                    const newName = name.replace(/\[faqs\]\[\d+\]/, '[faqs][' + index + ']');
                    $(this).attr('name', newName);
                }
            });

            // Masquer le bouton de suppression pour le premier élément
            $(this).find('.remove-faq').toggle(index > 0);
        });
    }

    // Initialiser l'état des boutons de suppression au chargement
    builderContainer.find('.faq-items').each(function () {
        updateFAQNumbers($(this));
    });
}
