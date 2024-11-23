<?php
class Call_To_Action_Block {
    public function render($data, $index) {
        ?>
        <div class="cta-block bg-white shadow-md rounded-lg p-6 mb-6 builder-block">
            <!-- Titre -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Titre :</label>
                <input type="text" name="builder_blocks[<?php echo $index; ?>][data][title]" value="<?php echo esc_attr($data['title'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <!-- Type de balise titre -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de balise titre :</label>
                <select name="builder_blocks[<?php echo $index; ?>][data][title_tag]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <?php
                    $title_tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
                    foreach ($title_tags as $tag) {
                        echo '<option value="' . esc_attr($tag) . '"' . selected($data['title_tag'] ?? 'h2', $tag, false) . '>' . esc_html($tag) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description :</label>
                <input type="text" name="builder_blocks[<?php echo $index; ?>][data][description]" value="<?php echo esc_attr($data['description'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <!-- Taille de police de la description -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Taille de police de la description :</label>
                <input type="number" name="builder_blocks[<?php echo $index; ?>][data][description_font_size]" value="<?php echo esc_attr($data['description_font_size'] ?? '16'); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <!-- Couleur du texte de description -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Couleur du texte de description :</label>
                <input type="color" name="builder_blocks[<?php echo $index; ?>][data][description_color]" value="<?php echo esc_attr($data['description_color'] ?? '#000000'); ?>" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-10 w-20">
            </div>            

            <!-- Type de background -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de background :</label>
                <select name="builder_blocks[<?php echo $index; ?>][data][bg_type]" class="bg-type-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="color" <?php selected($data['bg_type'] ?? 'color', 'color'); ?>>Couleur</option>
                    <option value="image" <?php selected($data['bg_type'] ?? 'color', 'image'); ?>>Image</option>
                </select>
            </div>

            <!-- Couleur de background -->
            <div class="mb-4 bg-color-field <?php echo ($data['bg_type'] ?? 'color') === 'image' ? 'hidden' : ''; ?>">
                <label class="block text-sm font-medium text-gray-700 mb-2">Couleur de background :</label>
                <input type="color" name="builder_blocks[<?php echo $index; ?>][data][bg_color]" value="<?php echo esc_attr($data['bg_color'] ?? '#ffffff'); ?>" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-10 w-20">
            </div>

            <!-- Image de background -->
            <div class="mb-4 bg-image-field <?php echo ($data['bg_type'] ?? 'color') === 'color' ? 'hidden' : ''; ?>">
                <label class="block text-sm font-medium text-gray-700 mb-2">Image de background :</label>
                <!-- Bouton pour choisir ou uploader une image -->
                <button type="button" class="image-selector-button bg-blue-500 text-white py-2 px-4 rounded mb-2">Ajouter ou sélectionner une image</button>

                <!-- Champ caché pour stocker l'URL de l'image -->
                <input type="hidden" name="builder_blocks[<?php echo esc_attr($index); ?>][data][bg_image]" value="<?php echo esc_url($data['bg_image'] ?? ''); ?>" class="bg-image-url">

                <!-- Affichage de l'image sélectionnée -->
                <div class="mt-4">
                    <?php if (!empty($data['bg_image'])) : ?>
                        <div class="relative bg-image-container">
                            <img src="<?php echo esc_url($data['bg_image']); ?>" alt="Background actuel" class="bg-image-preview max-w-xs">
                            <!-- Bouton de suppression -->
                            <button type="button" class="absolute top-0 right-0 bg-red-500 text-white p-1 rounded-full remove-bg-image">
                                &times;
                            </button>
                        </div>
                    <?php else : ?>
                        <div class="relative bg-image-container hidden">
                            <img src="" alt="Background actuel" class="bg-image-preview max-w-xs">
                            <!-- Bouton de suppression -->
                            <button type="button" class="absolute top-0 right-0 bg-red-500 text-white p-1 rounded-full remove-bg-image">
                                &times;
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <script type="text/template" id="cta-button-template-<?php echo esc_attr($index); ?>">
            <?php $this->render_cta_button_fields($index, 'BUTTON_INDEX', []); ?>
            </script>
            <!-- Boutons -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Boutons :</label>
                <div id="cta-buttons-container-<?php echo $index; ?>">
                <?php
                    $buttons = $data['buttons'] ?? [[]];
                    foreach ($buttons as $button_index => $button) {
                        $this->render_cta_button_fields($index, intval($button_index), $button);
                    }
                ?>
                </div>
                <?php if (count($buttons) < 2) : ?>
                    <button type="button" class="add-cta-button mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" data-block-index="<?php echo $index; ?>">
                        Ajouter un bouton
                    </button>
                <?php endif; ?>
            </div>            
        </div>
        <?php
    }


    private function render_cta_button_fields($block_index, $button_index, $button_data) {
        $button_index = intval($button_index);
        ?>
        <div class="cta-button-fields bg-gray-100 p-4 rounded-md mb-4">
            <h4 class="font-semibold mb-2">Bouton <?php echo esc_html($button_index + 1); ?></h4>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Texte du bouton :</label>
                <input type="text" name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][buttons][<?php echo esc_attr($button_index); ?>][text]" value="<?php echo esc_attr($button_data['text'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Couleur du bouton :</label>
                <input type="color" name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][buttons][<?php echo esc_attr($button_index); ?>][color]" value="<?php echo esc_attr($button_data['color'] ?? '#000000'); ?>" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-10 w-20">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">URL du bouton :</label>
                <input type="url" name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][buttons][<?php echo esc_attr($button_index); ?>][url]" value="<?php echo esc_url($button_data['url'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <?php if ($button_index > 0) : ?>
                <button type="button" class="remove-cta-button mt-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Supprimer ce bouton
                </button>
            <?php endif; ?>
        </div>
        <?php
    }

    public function sanitize($data, $post_id, $index) {
        $sanitized_data = array(
            'title' => isset($data['title']) ? sanitize_text_field($data['title']) : '',
            'title_tag' => isset($data['title_tag']) ? sanitize_text_field($data['title_tag']) : 'h2',
            'description' => isset($data['description']) ? wp_kses_post($data['description']) : '',
            'description_font_size' => isset($data['description_font_size']) ? intval($data['description_font_size']) : 16,
            'description_color' => isset($data['description_color']) ? sanitize_hex_color($data['description_color']) : '#000000',
            'bg_type' => isset($data['bg_type']) ? sanitize_text_field($data['bg_type']) : 'color',
            'bg_color' => isset($data['bg_color']) ? sanitize_hex_color($data['bg_color']) : '#ffffff',
        );

        // Gestion de l'image de background
        $sanitized_data['bg_image'] = esc_url_raw($data['bg_image'] ?? '');

        // Gestion des boutons
        $sanitized_data['buttons'] = array();
        if (isset($data['buttons']) && is_array($data['buttons'])) {
            foreach ($data['buttons'] as $button_index => $button) {
                $sanitized_data['buttons'][$button_index] = array(
                    'text' => isset($button['text']) ? sanitize_text_field($button['text']) : '',
                    'color' => isset($button['color']) ? sanitize_hex_color($button['color']) : '#000000',
                    'url' => isset($button['url']) ? esc_url_raw($button['url']) : '',
                );
            }
        }

        return $sanitized_data;
    }

    private function handle_image_upload($file, $post_id) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $upload_overrides = array('test_form' => false);
        $uploaded_file = wp_handle_upload($file, $upload_overrides);

        if ($uploaded_file && !isset($uploaded_file['error'])) {
            $file_name = basename($file['name']);
            $file_type = wp_check_filetype($file_name);

            $attachment_data = array(
                'post_mime_type' => $file_type['type'],
                'post_title'     => preg_replace('/\.[^.]+$/', '', $file_name),
                'post_content'   => '',
                'post_status'    => 'inherit'
            );

            $attachment_id = wp_insert_attachment($attachment_data, $uploaded_file['file'], $post_id);

            if (!is_wp_error($attachment_id)) {
                $attachment_metadata = wp_generate_attachment_metadata($attachment_id, $uploaded_file['file']);
                wp_update_attachment_metadata($attachment_id, $attachment_metadata);

                return array(
                    'id'  => $attachment_id,
                    'url' => $uploaded_file['url']
                );
            }
        }

        return false;
    }
}