jQuery(document).ready(function($) {
    const builderContainer = jQuery('#builder-container');
    const blocksContainer = jQuery('#builder-blocks');
    const addBlockButton = jQuery('#add-block');
    const blockTemplates = jQuery('#block-templates');

    let blockIndex = blocksContainer.children().length;

    // Gestion de l'ajout de block
    addBlockButton.on('click', function(e) {
        e.preventDefault();
        showBlockTypeDialog();
    });

    // // Gestion de la suppression de block
    // builderContainer.on('click', '.remove-block', function(e) {
    //     e.preventDefault();
    //     jQuery(this).closest('.builder-block').remove();
    //     updateBlockIndexes();
    // });

    function showBlockTypeDialog() {
        const dialog = jQuery('<div>', {
            title: 'Choisir le type de block',
            html: '<p>Sélectionnez le type de block à ajouter :</p>' +
                  '<button class="button" data-type="slider_home">Home Slider</button> ' +
                  '<button class="button" data-type="slider">Slider</button> ' +
                  '<button class="button" data-type="video">Vidéo</button>' +
                  '<button class="button" data-type="call_to_action">Call to action</button>' +
                  '<button class="button" data-type="alternate_visual">Visuel alterné</button>' +
                  '<button class="button" data-type="logos_carousel">Carousel logos</button>'
        }).dialog({
            modal: true,
            closeOnEscape: true,
            buttons: {
                "Annuler": function() {
                    jQuery(this).dialog('close');
                }
            },
            create: function() {
                jQuery(this).find('button[data-type]').on('click', function() {
                    const type = jQuery(this).data('type');
                    addBlock(type);
                    dialog.dialog('close');
                });
            }
        });
    }

    function addBlock(type) {
        const template = blockTemplates.find(`.builder-block[data-type="${type}"]`).clone();
        template.attr('data-index', blockIndex);
        template.find('input, textarea, select').each(function() {
            const name = jQuery(this).attr('name');
            if (name) {
                jQuery(this).attr('name', name.replace('TEMPLATE_INDEX', blockIndex));
            }
        });
        blocksContainer.append(template);
        blockIndex++;
        updateBlockIndexes();
    }

    function updateBlockIndexes() {
        blocksContainer.children('.builder-block').each(function(index) {
            jQuery(this).attr('data-index', index);
            jQuery(this).find('input, textarea, select').each(function() {
                const name = jQuery(this).attr('name');
                if (name) {
                    jQuery(this).attr('name', name.replace(/\[(\d+)\]/, '[' + index + ']'));
                }
            });
        });
    } 

    // On vérifie si les fonctions existent avant de les appeler
    if (typeof admin_slider_home_scripts === 'function') {
        admin_slider_home_scripts($, builderContainer);
    } 

    if (typeof admin_slider_scripts === 'function') {
        admin_slider_scripts($, builderContainer);
    }       

    if (typeof admin_cta_scripts === 'function') {
        admin_cta_scripts($, builderContainer);
    }       

    if (typeof admin_video_scripts === 'function') {
        admin_video_scripts();
    }    

    if (typeof admin_alternate_visual_scripts === 'function') {
        admin_alternate_visual_scripts($, builderContainer);
    }    

    if (typeof admin_logos_carousel_scripts === 'function') {
        admin_logos_carousel_scripts($, builderContainer);
    }           

});

/* Gestion de la modal de confirmation de suppression d'un block */
jQuery(document).on('click', '.remove-block', function (e) {
    e.preventDefault();
    var blockToRemove = jQuery(this).closest('.builder-block');
    
    // Affiche la modale en modifiant la propriété display
    jQuery('#confirm-delete-modal').css('display', 'flex');

    // Gérer le click sur le bouton "Supprimer" dans la modale
    jQuery('#confirm-delete').off('click').on('click', function (e) {
        e.preventDefault();
        blockToRemove.remove();
        jQuery('#confirm-delete-modal').css('display', 'none');
    });

    // Gérer le click sur le bouton "Annuler" dans la modale
    jQuery('#cancel-delete').on('click', function (e) {
        e.preventDefault();
        jQuery('#confirm-delete-modal').css('display', 'none');
    });
});
