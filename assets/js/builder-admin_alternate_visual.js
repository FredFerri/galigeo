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

    /* Gestion de la suppression dynamique des images */
    const removeImgButtons = document.querySelectorAll('.alt-remove-img');

    removeImgButtons.forEach(button => {
        button.addEventListener('click', function () {
            const ImgContainer = this.closest('.relative');
            const ImgIndex = this.getAttribute('data-img-index');

            // Masquer l'image et le bouton
            ImgContainer.style.display = 'none';

            // Désactiver l'input correspondant pour qu'il ne soit pas envoyé dans le formulaire
            const hiddenInput = ImgContainer.querySelector('input[type="hidden"]');
            if (hiddenInput) {
                hiddenInput.disabled = true;
            }
        });
    });    
}    