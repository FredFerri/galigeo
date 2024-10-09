function admin_logos_carousel_scripts($, builderContainer) {

    builderContainer.on('change', '.lc_background_type_selector', function() {
        const bgType = $(this).val();
        const ctaBlock = $(this).closest('.builder-block');
        ctaBlock.find('.lc_bg_color_field').toggle(bgType === 'color');
        ctaBlock.find('.lc_bg_image_field').toggle(bgType === 'image');
    });

	/* Gestion de la suppression dynamique des logos */

	alert('ok');

    const removeLogoButtons = document.querySelectorAll('.lc-remove-logo');

    removeLogoButtons.forEach(button => {
        button.addEventListener('click', function () {
        	alert("zz");
            const logoContainer = this.closest('.relative');
            const logoIndex = this.getAttribute('data-logo-index');

            // Masquer l'image et le bouton
            logoContainer.style.display = 'none';

            // Désactiver l'input correspondant pour qu'il ne soit pas envoyé dans le formulaire
            const hiddenInput = logoContainer.querySelector('input[type="hidden"]');
            if (hiddenInput) {
                hiddenInput.disabled = true;
            }
        });
    });

};
