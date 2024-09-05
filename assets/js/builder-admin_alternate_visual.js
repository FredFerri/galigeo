function admin_alternate_visual_scripts($, builderContainer) {    
    // Gestion du type de background pour le CTA
    builderContainer.on('change', '.av_background_type_selector', function() {
        const bgType = $(this).val();
        const ctaBlock = $(this).closest('.builder-block');
        ctaBlock.find('.av_bg-color-field').toggle(bgType === 'color');
        ctaBlock.find('.av_bg-image-field').toggle(bgType === 'image');
    });

    builderContainer.on('change', '.av_show_button_checkbox', function() {
        const showBtnVal = $(this).val();
        $(this).parent().parent().next('.av_button-options').toggleClass('hidden');

    });    
}    