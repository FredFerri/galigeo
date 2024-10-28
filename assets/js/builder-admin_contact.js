function admin_contact_block_scripts($, builderContainer) {
    // Gestion du type de background
    builderContainer.on('change', '.bg-type-select', function() {
        const bgType = $(this).val();
        const contactBlock = $(this).closest('.builder-block');
        contactBlock.find('.bg-color-field').toggle(bgType === 'color');
        contactBlock.find('.bg-image-field').toggle(bgType === 'image');
    });
}
