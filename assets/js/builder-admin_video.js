function admin_video_scripts() {
    // Gérer l'affichage/masquage des options du bouton
    jQuery(document).on('change', '.galigeo-builder-video .show_button_checkbox', function() {
        var index = jQuery(this).data('index');
        var buttonOptions = jQuery('.galigeo-builder-video .button-options[data-index="' + index + '"]');
        if (this.checked) {
            buttonOptions.removeClass('hidden').addClass('block');
        } else {
            buttonOptions.removeClass('block').addClass('hidden');
        }
    });
};
