jQuery(document).ready(function($) {
    const builderContainer = jQuery('#builder-container');
    const blocksContainer = jQuery('#builder-blocks');
    const addBlockButton = jQuery('#add-block');
    const blockTemplates = jQuery('#block-templates');

    var blockIndex = blocksContainer.children().length;

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
                  '<button class="button" data-type="logos_carousel">Carousel logos</button>' +
                  '<button class="button" data-type="import_html">Import HTML</button>' +
                  '<button class="button" data-type="simple_columns">Colonnes simples</button>' +
                  '<button class="button" data-type="texte">Texte</button>' +
                  '<button class="button" data-type="contact">Contact</button>' +
                  '<button class="button" data-type="testimonials">Témoignages</button>' +
                  '<button class="button" data-type="heros_cas_client">Heros cas client</button>' +
                  '<button class="button" data-type="faq">FAQ</button>' +
                  '<button class="button" data-type="cas_client">Cas client</button>'
        }).dialog({
            modal: true,
            closeOnEscape: true,
            dialogClass: 'galigeo-dialog',
            width: '40%',
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
        // alert(blockIndex);
        const template = blockTemplates.find(`.builder-block[data-type="${type}"]`).clone();
        template.attr('data-index', blockIndex);
        template.find('input, textarea, select').each(function() {
            const name = jQuery(this).attr('name');
            if (name) {
                jQuery(this).attr('name', name.replace('TEMPLATE_INDEX', blockIndex));
            }
        });

        // CODE SPECIFIQUE AU BLOCK "TEXTE"
        // Vérifier si le bloc texte est présent dans le template
        if (template.find(`#text-buttons-container-TEMPLATE_INDEX`).length > 0) {
            // Mettre à jour l'ID du container des boutons pour le bloc Texte
            template.find(`#text-buttons-container-TEMPLATE_INDEX`).attr('id', `text-buttons-container-${blockIndex}`);

            // Mettre à jour l'ID du template de bouton pour le bloc Texte
            template.find(`#text-button-template-TEMPLATE_INDEX`).attr('id', `text-button-template-${blockIndex}`);

            // Mettre à jour le data-block-index du bouton d'ajout de bouton
            template.find('.add-text-button').attr('data-block-index', blockIndex);

            // Mettre à jour les attributs name dans le template des boutons
            let buttonTemplate = template.find(`#text-button-template-${blockIndex}`);
            buttonTemplate.html(buttonTemplate.html().replace(/TEMPLATE_INDEX/g, blockIndex));            
        }

        // CODE SPECIFIQUE AU BLOCK "CTA"
        // Vérifier si le bloc texte est présent dans le template
        if (template.find(`#cta-buttons-container-TEMPLATE_INDEX`).length > 0) {
            // Mettre à jour l'ID du container des boutons pour le bloc Texte
            template.find(`#cta-buttons-container-TEMPLATE_INDEX`).attr('id', `cta-buttons-container-${blockIndex}`);

            // Mettre à jour l'ID du template de bouton pour le bloc Texte
            template.find(`#cta-button-template-TEMPLATE_INDEX`).attr('id', `cta-button-template-${blockIndex}`);

            // Mettre à jour le data-block-index du bouton d'ajout de bouton
            template.find('.add-cta-button').attr('data-block-index', blockIndex);

            // Mettre à jour les attributs name dans le template des boutons
            let buttonTemplate = template.find(`#cta-button-template-${blockIndex}`);
            buttonTemplate.html(buttonTemplate.html().replace(/TEMPLATE_INDEX/g, blockIndex));            
        }        

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
    if (typeof admin_text_block_scripts === 'function') {
        admin_text_block_scripts($, builderContainer);
    }   
    if (typeof admin_contact_block_scripts === 'function') {
        admin_contact_block_scripts($, builderContainer);
    }  
    if (typeof admin_testimonials_scripts === 'function') {
        admin_testimonials_scripts($, builderContainer);
    }    
    if (typeof admin_import_html_scripts === 'function') {
        admin_import_html_scripts($);
    }     
    if (typeof admin_faq_scripts === 'function') {
        admin_faq_scripts($, builderContainer);
    }               


    /* Gestion de la modal de confirmation de suppression d'un block */
    jQuery(document).on('click', '.remove-block', function (e) {
        e.preventDefault();
        var blockToRemove = jQuery(this).closest('.builder-block');

        // Affiche la modale en modifiant la propriété display
        jQuery('#confirm-delete-modal').css('display', 'flex');
        jQuery('#confirm-delete-modal').removeClass('hidden');

        // Gérer le click sur le bouton "Supprimer" dans la modale
        jQuery('#confirm-delete').off('click').on('click', function (e) {
            e.preventDefault();
            blockToRemove.remove();
            updateBlockIndexes();
            jQuery('#confirm-delete-modal').css('display', 'none');
        });

        // Gérer le click sur le bouton "Annuler" dans la modale
        jQuery('#cancel-delete').on('click', function (e) {
            e.preventDefault();
            jQuery('#confirm-delete-modal').css('display', 'none');
        });
    });
});

// Code qui gère l'affichage d'un message de confirmation lorsqu'on quitte la page alors que des changements 
// non sauvegardés sont en cours
(function ($) {
    $(document).ready(function () {
        let isDirty = false;

        // Détecter les changements dans les inputs, selects et textareas
        $('#builder-container').on('input change', 'input, textarea, select', function () {
            isDirty = true;
        });

        // Écouter l'événement avant de quitter la page
        window.onbeforeunload = function (e) {
            if (isDirty) {
                // Message affiché par certains navigateurs
                return "Vous avez des modifications non sauvegardées. Êtes-vous sûr de vouloir quitter cette page ?";
            }
        };

        // Écouter les clics sur les liens de navigation
        $(document).on('click', 'a', function (e) {
            if (isDirty) {
                const confirmation = confirm(
                    "Vous avez des modifications non sauvegardées. Êtes-vous sûr de vouloir quitter cette page ?"
                );
                if (!confirmation) {
                    e.preventDefault();
                }
            }
        });

        // Réinitialiser le drapeau "isDirty" après la sauvegarde
        $('#publish, #save-post').on('click', function () {
            isDirty = false;
        });
    });
})(jQuery);
