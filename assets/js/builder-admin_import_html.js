function admin_import_html_scripts($) {

        // Fonction pour gérer l'ajout ou la sélection d'une image
        document.addEventListener('click', (event) => {
            if (event.target.classList.contains('html-image-selector-button')) {
                console.log('Bouton "Ajouter ou sélectionner une image" cliqué.');

                const button = event.target; // Bouton cliqué
                const block = button.closest('.builder-block'); // Bloc parent du bouton
                console.dir(block);

                if (!block) {
                    console.error('Bloc parent introuvable.');
                    return;
                }

                // Références spécifiques au bloc
                const imageUrlInput = block.querySelector('.html-bg-image-url');
                const imagePreview = block.querySelector('.html-bg-image-preview');
                const imageContainer = block.querySelector('.html-bg-image-container');
                const removeImageButton = block.querySelector('.remove-html-bg-image');

                // Créer une nouvelle instance de mediaFrame pour chaque interaction
                const mediaFrame = wp.media({
                    title: 'Choisir une image',
                    button: { text: 'Utiliser cette image' },
                    multiple: false,
                });

                // Attacher l'événement de sélection pour ce bloc
                mediaFrame.on('select', function () {
                    const attachment = mediaFrame.state().get('selection').first().toJSON();
                    console.log('Image sélectionnée :', attachment);

                    // Mise à jour des champs pour le bloc actif
                    imageUrlInput.value = attachment.url;
                    imagePreview.src = attachment.url;
                    imageContainer.classList.remove('hidden');
                    removeImageButton.classList.remove('hidden');

                    console.log('Image mise à jour pour le bloc actif.');
                });

                // Ouvrir la médiathèque
                mediaFrame.open();
            }
        });

        // Gestion du bouton "Supprimer l'image"
        document.addEventListener('click', (event) => {
            if (event.target.classList.contains('remove-html-bg-image')) {
                console.log('Bouton "Supprimer l\'image" cliqué.');

                const block = event.target.closest('.builder-block');
                console.dir(block);

                if (block) {
                    const imageUrlInput = block.querySelector('.html-bg-image-url');
                    const imagePreview = block.querySelector('.html-bg-image-preview');
                    const imageContainer = block.querySelector('.html-bg-image-container');

                    // Réinitialiser les champs liés à l'image
                    imageUrlInput.value = '';
                    imagePreview.src = '';
                    imageContainer.classList.add('hidden');

                    console.log('Image supprimée avec succès.');
                } else {
                    console.error('Impossible de trouver le bloc parent pour la suppression de l\'image.');
                }
            }
        });

        // Fonction pour basculer les champs entre image et couleur
        function toggleBackgroundFields(block) {
            const bgType = block.querySelector('.html-background-type-select').value;
            const colorField = block.querySelector('.html-bg-color-field');
            const imageField = block.querySelector('.html-bg-image-field');

            console.log(`Type sélectionné : ${bgType}`);

            if (bgType === 'image') {
                colorField.classList.add('hidden');
                imageField.classList.remove('hidden');
            } else {
                colorField.classList.remove('hidden');
                imageField.classList.add('hidden');
            }
        }

        // Gestionnaire délégué pour le changement de type de background
        document.addEventListener('change', (event) => {
            if (event.target.classList.contains('html-background-type-select')) {
                console.log('Type de background changé.');

                const block = event.target.closest('.import-html-block');
                if (block) {
                    toggleBackgroundFields(block);
                } else {
                    console.error('Bloc parent introuvable.');
                }
            }
        });

        // Initialisation des champs de background
        document.querySelectorAll('.import-html-block').forEach(block => {
            toggleBackgroundFields(block);

            const bgTypeSelect = block.querySelector('.html-background-type-select');
            bgTypeSelect.addEventListener('change', () => {
                toggleBackgroundFields(block);
            });
        });

}

