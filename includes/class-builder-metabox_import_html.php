<?php
class Import_HTML_Block {
    public function render($data, $index) {
        ?>
        <div class="import-html-block bg-white shadow-md rounded-lg p-6 mb-6">
            <!-- Titre -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Titre :</label>
                <input type="text" name="builder_blocks[<?php echo $index; ?>][data][html_title]" value="<?php echo esc_attr($data['html_title'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <!-- Type de balise titre -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de titre :</label>
                <select name="builder_blocks[<?php echo $index; ?>][data][html_title_tag]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <?php
                    $title_tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
                    foreach ($title_tags as $tag) {
                        echo '<option value="' . esc_attr($tag) . '"' . selected($data['html_title_tag'] ?? 'h2', $tag, false) . '>' . esc_html($tag) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <!-- Code HTML -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Code HTML :</label>
                <textarea name="builder_blocks[<?php echo $index; ?>][data][html_code]" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"><?php echo esc_textarea($data['html_code'] ?? ''); ?></textarea>
            </div>

            <!-- ID -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">ID du block (facultatif) :</label>
                <input type="text" name="builder_blocks[<?php echo $index; ?>][data][html_id]" value="<?php echo esc_attr($data['html_id'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <!-- Type de background -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de background :</label>
                <select name="builder_blocks[<?php echo $index; ?>][data][html_background_type]" class="html-background-type-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="color" <?php selected($data['html_background_type'] ?? 'color', 'color'); ?>>Couleur</option>
                    <option value="image" <?php selected($data['html_background_type'] ?? 'color', 'image'); ?>>Image</option>
                </select>
            </div>

            <!-- Couleur de background -->
            <div class="mb-4 html-bg-color-field <?php echo ($data['html_background_type'] ?? 'color') === 'image' ? 'hidden' : ''; ?>">
                <label class="block text-sm font-medium text-gray-700 mb-2">Couleur de background :</label>
                <input type="color" name="builder_blocks[<?php echo $index; ?>][data][html_background_color]" value="<?php echo esc_attr($data['html_background_color'] ?? '#ffffff'); ?>" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-10 w-20">
            </div>

            <!-- Image de background -->
            <div class="mb-4 html-bg-image-field <?php echo ($data['html_background_type'] ?? 'color') === 'color' ? 'hidden' : ''; ?>">
                <label class="block text-sm font-medium text-gray-700 mb-2">Image de background :</label>
                <!-- Bouton pour choisir ou uploader une image -->
                <button type="button" class="html-image-selector-button bg-blue-500 text-white py-2 px-4 rounded mb-2">Ajouter ou sélectionner une image</button>

                <!-- Champ caché pour stocker l'URL de l'image -->
                <input type="hidden" name="builder_blocks[<?php echo esc_attr($index); ?>][data][html_background_image]" value="<?php echo esc_url($data['html_background_image'] ?? ''); ?>" class="html-bg-image-url">

                <!-- Affichage de l'image sélectionnée -->
                <div class="mt-4">
                    <?php if (!empty($data['html_background_image'])) : ?>
                        <div class="relative html-bg-image-container">
                            <img src="<?php echo esc_url($data['html_background_image']); ?>" alt="Background actuel" class="html-bg-image-preview max-w-xs">
                            <!-- Bouton de suppression -->
                            <button type="button" class="absolute top-0 right-0 bg-red-500 text-white p-1 rounded-full remove-html-bg-image">
                                &times;
                            </button>
                        </div>
                    <?php else : ?>
                        <div class="relative html-bg-image-container hidden">
                            <img src="" alt="Background actuel" class="html-bg-image-preview max-w-xs">
                            <!-- Bouton de suppression -->
                            <button type="button" class="absolute top-0 right-0 bg-red-500 text-white p-1 rounded-full remove-html-bg-image">
                                &times;
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <script>
            
            
        </script>

        <?php
    }

    public function sanitize($data, $post_id, $index) {
        $sanitized_data = array(
            'html_title' => sanitize_text_field($data['html_title'] ?? ''),
            'html_title_tag' => sanitize_text_field($data['html_title_tag'] ?? 'h2'),
            'html_code' => wp_unslash($data['html_code'] ?? ''),
            'html_id' => sanitize_text_field($data['html_id'] ?? ''),
            'html_background_type' => sanitize_text_field($data['html_background_type'] ?? 'color'),
            'html_background_color' => sanitize_hex_color($data['html_background_color'] ?? '#ffffff'),
            'html_background_image' => esc_url_raw($data['html_background_image'] ?? '')
        );

        return $sanitized_data;
    }
}
