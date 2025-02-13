function admin_contact_block_scripts($, builderContainer) {
    // Gestion du type de background
    builderContainer.on('change', '.contact-block .bg-type-select', function() {
        const bgType = $(this).val();
        const contactBlock = $(this).closest('.builder-block');
        if (bgType === "color") {
            contactBlock.find('.bg-color-field').removeClass("hidden");
            contactBlock.find('.bg-image-field').addClass("hidden");
        } else if (bgType === "image") {
            contactBlock.find('.bg-color-field').addClass("hidden");
            contactBlock.find('.bg-image-field').removeClass("hidden");
        }
    });
}
