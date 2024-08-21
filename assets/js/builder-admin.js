jQuery(document).ready(function($) {
    const builderContainer = $('#builder-container');
    const blocksContainer = $('#builder-blocks');
    const addBlockButton = $('#add-block');
    const blockTemplates = $('#block-templates');

    let blockIndex = blocksContainer.children().length;

    addBlockButton.on('click', function(e) {
        e.preventDefault();
        showBlockTypeDialog();
    });

    builderContainer.on('click', '.remove-block', function(e) {
        e.preventDefault();
        $(this).closest('.builder-block').remove();
        updateBlockIndexes();
    });

    function showBlockTypeDialog() {
        const dialog = $('<div>', {
            title: 'Choisir le type de block',
            html: '<p>Sélectionnez le type de block à ajouter :</p>' +
                  '<button class="button" data-type="slider">Slider</button> ' +
                  '<button class="button" data-type="video">Vidéo</button>'
        }).dialog({
            modal: true,
            buttons: {
                "Annuler": function() {
                    $(this).dialog('close');
                }
            }
        });

        dialog.find('button[data-type]').on('click', function() {
            const type = $(this).data('type');
            addBlock(type);
            dialog.dialog('close');
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
        // Assurez-vous que le champ de type est inclus
        template.append(`<input type="hidden" name="builder_blocks[${blockIndex}][type]" value="${type}">`);
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
});