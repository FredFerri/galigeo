function admin_heros_cas_client_scripts($, builderContainer) {
    // Gestion du type de background pour Hero Cas Client
    builderContainer.on('change', '.bg-type-select', function() {
        const bgType = $(this).val();
        const heroBlock = $(this).closest('.builder-block');
        heroBlock.find('.bg-color-field').toggle(bgType === 'color');
        heroBlock.find('.bg-image-field').toggle(bgType === 'image');
    });
}
