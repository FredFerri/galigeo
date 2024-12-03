(function ($) {
    $(document).ready(function () {
        // Fonction générique pour gérer les uploads
        function handleMediaFrame(
            buttonClass,
            containerClass,
            removeClass,
            multiple = false
        ) {
            $(document).on("click", buttonClass, function (event) {
                event.preventDefault();

                const button = $(this);
                const container = button.siblings(containerClass);

                // Récupérer le data-name du bouton
                const dataName = button.data("name");

                if (!dataName) {
                    console.error("L'attribut data-name est manquant sur le bouton.");
                    return;
                }

                // Ouvrir le Media Frame WordPress
                const mediaFrame = wp.media({
                    title: "Choisir une image",
                    button: { text: "Utiliser cette image" },
                    multiple: multiple, // Mode multiple ou non
                });

                // Lorsqu'une image est sélectionnée
                mediaFrame.on("select", function () {
                    const selection = mediaFrame.state().get("selection");

                    if (multiple) {
                        // Gestion des images multiples
                        selection.each(function (attachment) {
                            attachment = attachment.toJSON();

                            // Injecter un nouvel élément dans le container
                            const logoHtml = `
                                <div class="relative lc-logo-item">
                                    <img src="${attachment.url}" alt="Logo" class="max-w-full h-auto">
                                    <button type="button" class="absolute top-0 right-0 bg-red-500 text-white p-1 rounded-full lc-remove-logo">
                                        &times;
                                    </button>
                                    <input type="hidden" name="${dataName}[]" value="${attachment.url}">
                                </div>`;
                            container.append(logoHtml);
                        });
                    } else {
                        // Gestion d'une seule image (si nécessaire)
                        const attachment = selection.first().toJSON();
                        container.html(`
                            <div class="relative lc-logo-item">
                                <img src="${attachment.url}" alt="Logo" class="max-w-full h-auto">
                                <button type="button" class="absolute top-0 right-0 bg-red-500 text-white p-1 rounded-full lc-remove-logo">
                                    &times;
                                </button>
                                <input type="hidden" name="${dataName}" value="${attachment.url}">
                            </div>
                        `);
                    }
                });

                mediaFrame.open();
            });

            // Gestion du bouton "Supprimer"
            $(document).on("click", removeClass, function (event) {
                event.preventDefault();
                const logoItem = $(this).closest(".lc-logo-item");
                logoItem.remove();
            });
        }

        // Initialisation pour le champ "Logos multiples"
        handleMediaFrame(
            ".lc-logo-selector", // Bouton d'upload
            ".lc-logos-container", // Conteneur des images
            ".lc-remove-logo", // Bouton de suppression
            true // Mode multiple
        );

        // Initialisation pour le champ "Image de background" (si nécessaire)
        handleMediaFrame(
            ".lc-bg-image-selector", // Bouton d'upload
            ".lc-bg-image-container", // Conteneur
            ".lc-remove-bg-image", // Bouton de suppression
            false // Mode unique
        );

        // Gestion du type de background (couleur ou image)
        $(document).on("change", ".lc_background_type_selector", function () {
            const bgType = $(this).val();
            const block = $(this).closest(".client-carousel-block");
            block.find(".lc_bg_color_field").toggleClass("hidden", bgType !== "color");
            block.find(".lc_bg_image_field").toggleClass("hidden", bgType !== "image");
        });
    });
})(jQuery);
