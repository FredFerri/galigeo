<?php
class Text_Block {
    public function render($data, $index) {
        ?>
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
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
                        echo '<option value="' . $tag . '"' . selected($data['title_tag'] ?? 'h2', $tag, false) . '>' . $tag . '</option>';
                    }
                    ?>
                </select>
            </div>

            <!-- Sous-titre -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Sous-titre :</label>
                <input type="text" name="builder_blocks[<?php echo $index; ?>][data][subtitle]" value="<?php echo esc_attr($data['subtitle'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <!-- Type de balise sous-titre -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de balise sous-titre :</label>
                <select name="builder_blocks[<?php echo $index; ?>][data][subtitle_tag]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <?php
                    foreach ($title_tags as $tag) {
                        echo '<option value="' . $tag . '"' . selected($data['subtitle_tag'] ?? 'h3', $tag, false) . '>' . $tag . '</option>';
                    }
                    ?>
                </select>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description :</label>
                <textarea name="builder_blocks[<?php echo $index; ?>][data][description]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" rows="4"><?php echo esc_textarea($data['description'] ?? ''); ?></textarea>
            </div>

            <!-- Taille de la police de la description -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Taille de police de la description :</label>
                <input type="number" name="builder_blocks[<?php echo $index; ?>][data][description_font_size]" value="<?php echo esc_attr($data['description_font_size'] ?? '16'); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <!-- Couleur du texte de description -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Couleur du texte de description :</label>
                <input type="color" name="builder_blocks[<?php echo $index; ?>][data][description_color]" value="<?php echo esc_attr($data['description_color'] ?? '#000000'); ?>" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-10 w-20">
            </div>

            <!-- Boutons -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Boutons :</label>
                <div id="text-buttons-container-<?php echo $index; ?>">
                    <?php
                    if (!empty($data['buttons']) && is_array($data['buttons'])) {
                        foreach ($data['buttons'] as $button_index => $button_data) {
                            $this->render_button_fields($index, $button_index, $button_data);
                        }
                    }
                    ?>
                </div>
                <button type="button" class="add-text-button mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" data-block-index="<?php echo $index; ?>">
                    Ajouter un bouton
                </button>
            </div>

            <!-- Couleur de background -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Couleur de background :</label>
                <input type="color" name="builder_blocks[<?php echo $index; ?>][data][bg_color]" value="<?php echo esc_attr($data['bg_color'] ?? '#ffffff'); ?>" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-10 w-20">
            </div>
        </div>

        <script type="text/template" id="text-button-template-<?php echo esc_attr($index); ?>">
            <?php $this->render_button_fields($index, 'BUTTON_INDEX', []); ?>
        </script>

        <?php
    }


    private function render_button_fields($block_index, $button_index, $button_data) {
        ?>
        <div class="button-fields bg-gray-100 p-4 rounded-md mb-4">
            <h4 class="font-semibold mb-2">Bouton <span class="button-number"><?php echo esc_html($button_index + 1); ?></span></h4>

            <!-- Texte du bouton -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Texte du bouton :</label>
                <input type="text" name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][buttons][<?php echo esc_attr($button_index); ?>][text]" value="<?php echo esc_attr($button_data['text'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <!-- URL du bouton -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">URL du bouton :</label>
                <input type="url" name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][buttons][<?php echo esc_attr($button_index); ?>][url]" value="<?php echo esc_url($button_data['url'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <!-- Couleur du bouton -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Couleur du bouton :</label>
                <input type="color" name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][buttons][<?php echo esc_attr($button_index); ?>][color]" value="<?php echo esc_attr($button_data['color'] ?? '#000000'); ?>" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-10 w-20">
            </div>

            <!-- Bouton de suppression -->
            <button type="button" class="remove-button mt-4 bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                Supprimer ce bouton
            </button>
        </div>
        <?php
    }



    public function sanitize($data, $post_id, $index) {
        var_dump($data);
        $sanitized_data = array(
            'title' => isset($data['title']) ? sanitize_text_field($data['title']) : '',
            'title_tag' => isset($data['title_tag']) ? sanitize_text_field($data['title_tag']) : 'h2',
            'subtitle' => isset($data['subtitle']) ? sanitize_text_field($data['subtitle']) : '',
            'subtitle_tag' => isset($data['subtitle_tag']) ? sanitize_text_field($data['subtitle_tag']) : 'h3',
            'description' => isset($data['description']) ? wp_kses_post($data['description']) : '',
            'description_font_size' => isset($data['description_font_size']) ? intval($data['description_font_size']) : 16,
            'description_color' => isset($data['description_color']) ? sanitize_hex_color($data['description_color']) : '#000000',
            'bg_type' => isset($data['bg_type']) ? sanitize_text_field($data['bg_type']) : 'color',
            'bg_color' => isset($data['bg_color']) ? sanitize_hex_color($data['bg_color']) : '#ffffff',
        );

        // Gestion des boutons
        $sanitized_data['buttons'] = array();
        if (isset($data['buttons']) && is_array($data['buttons'])) {
            foreach ($data['buttons'] as $button_index => $button) {
                // Sanitize each button's data
                $sanitized_button = array(
                    'text' => isset($button['text']) ? sanitize_text_field($button['text']) : '',
                    'url' => isset($button['url']) ? esc_url_raw($button['url']) : '',
                    'color' => isset($button['color']) ? sanitize_hex_color($button['color']) : '#000000',
                );

                // Ajouter le bouton sanitizé à l'array
                $sanitized_data['buttons'][$button_index] = $sanitized_button;
            }
        }
        return $sanitized_data;
    }
}
