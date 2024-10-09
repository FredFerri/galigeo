jQuery(document).ready(function($) {
    const builderContainer = $('#builder-container');
    const blocksContainer = $('#builder-blocks');
    const addBlockButton = $('#add-block');
    const blockTemplates = $('#block-templates');

    let blockIndex = blocksContainer.children().length;

    // Gestion de l'ajout de block
    addBlockButton.on('click', function(e) {
        e.preventDefault();
        showBlockTypeDialog();
    });

    // // Gestion de la suppression de block
    // builderContainer.on('click', '.remove-block', function(e) {
    //     e.preventDefault();
    //     $(this).closest('.builder-block').remove();
    //     updateBlockIndexes();
    // });

    function showBlockTypeDialog() {
        const dialog = $('<div>', {
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
                    $(this).dialog('close');
                }
            },
            create: function() {
                $(this).find('button[data-type]').on('click', function() {
                    const type = $(this).data('type');
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
            const name = $(this).attr('name');
            if (name) {
                $(this).attr('name', name.replace('TEMPLATE_INDEX', blockIndex));
            }
        });
        blocksContainer.append(template);
        blockIndex++;
        updateBlockIndexes();
    }

    function updateBlockIndexes() {
        blocksContainer.children('.builder-block').each(function(index) {
            $(this).attr('data-index', index);
            $(this).find('input, textarea, select').each(function() {
                const name = $(this).attr('name');
                if (name) {
                    $(this).attr('name', name.replace(/\[(\d+)\]/, '[' + index + ']'));
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
    let deleteButton = null;  // Bouton de suppression qui déclenche la modale
    // Clic sur un bouton de suppression
    document.querySelectorAll('.remove-block').forEach(function (button) {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            deleteButton = this;  // Stocker le bouton de suppression qui a été cliqué
            document.getElementById('confirm-delete-modal').classList.remove('hidden');  // Afficher la modale
        });
    });

    // Annuler la suppression
    document.getElementById('cancel-delete').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('confirm-delete-modal').classList.add('hidden');
        deleteButton = null;  // Réinitialiser le bouton stocké
    });

    // Confirmer la suppression
    document.getElementById('confirm-delete').addEventListener('click', function (e) {
        if (deleteButton) {
            e.preventDefault();
            const blockToDelete = deleteButton.closest('.builder-block');  // Trouver le block à supprimer
            blockToDelete.remove();  // Supprimer le block
            document.getElementById('confirm-delete-modal').classList.add('hidden');  // Masquer la modale
            deleteButton = null;  // Réinitialiser le bouton stocké
        }
    });
