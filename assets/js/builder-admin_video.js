function admin_video_scripts() {
    // Gérer l'affichage/masquage des options du bouton
    jQuery(document).on('change', '.builder-block[data-type="video"] .show_button_checkbox', function() {
        var index = jQuery(this).data('index');
        var buttonOptions = jQuery('.builder-block[data-type="video"] .button-options[data-index="' + index + '"]');
        if (this.checked) {
            buttonOptions.removeClass('hidden').addClass('block');
        } else {
            buttonOptions.removeClass('block').addClass('hidden');
        }
    });
};
