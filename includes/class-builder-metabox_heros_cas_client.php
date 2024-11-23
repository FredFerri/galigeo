<?php
class Heros_Cas_Client_Block {
    public function render($data, $index) {
        ?>
        <div class="heros-cas-client-block bg-white shadow-md rounded-lg p-6 mb-6 builder-block">
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

            <!-- Texte "Success Story" -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Texte "Success Story" :</label>
                <input type="text" name="builder_blocks[<?php echo $index; ?>][data][success_story]" value="<?php echo esc_attr($data['success_story'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description :</label>
                <textarea name="builder_blocks[<?php echo $index; ?>][data][description]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"><?php echo esc_textarea($data['description'] ?? ''); ?></textarea>
            </div>

            <!-- Image principale -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Image :</label>
                <button type="button" class="main-image-selector bg-blue-500 text-white py-2 px-4 rounded mb-2">Ajouter ou sélectionner une image</button>
                <input type="hidden" name="builder_blocks[<?php echo $index; ?>][data][main_image]" value="<?php echo esc_url($data['main_image'] ?? ''); ?>" class="main-image-url">
                <div class="mt-4 main-image-container <?php echo empty($data['main_image']) ? 'hidden' : ''; ?>">
                    <img src="<?php echo esc_url($data['main_image']); ?>" alt="Image actuelle" class="main-image-preview max-w-xs">
                    <button type="button" class="bg-red-500 text-white p-1 rounded-full remove-main-image">&times;</button>
                </div>
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
                <button type="button" class="bg-image-selector bg-blue-500 text-white py-2 px-4 rounded mb-2">Ajouter ou sélectionner une image</button>
                <input type="hidden" name="builder_blocks[<?php echo $index; ?>][data][bg_image]" value="<?php echo esc_url($data['bg_image'] ?? ''); ?>" class="bg-image-url">
                <div class="mt-4 bg-image-container <?php echo empty($data['bg_image']) ? 'hidden' : ''; ?>">
                    <img src="<?php echo esc_url($data['bg_image']); ?>" alt="Background actuel" class="bg-image-preview max-w-xs">
                    <button type="button" class="bg-red-500 text-white p-1 rounded-full remove-bg-image">&times;</button>
                </div>
            </div>
        </div>
        <?php
    }

    public function sanitize($data, $post_id, $index) {
        $sanitized_data = array(
            'title' => sanitize_text_field($data['title'] ?? ''),
            'title_tag' => sanitize_text_field($data['title_tag'] ?? 'h2'),
            'description' => wp_kses_post($data['description'] ?? ''),
            'success_story' => sanitize_text_field($data['success_story'] ?? ''),
            'bg_type' => sanitize_text_field($data['bg_type'] ?? 'color'),
            'bg_color' => sanitize_hex_color($data['bg_color'] ?? '#ffffff'),
        );

        $sanitized_data['main_image'] = $this->sanitize_image($data, 'main_image', $post_id, $index);
        if ($sanitized_data['bg_type'] === 'image') {
            $sanitized_data['bg_image'] = $this->sanitize_image($data, 'bg_image', $post_id, $index);
        }

        return $sanitized_data;
    }

    private function sanitize_image($data, $key, $post_id, $index) {
        if (!empty($data[$key])) {
            return esc_url_raw($data[$key]);
        }
        return '';
    }

    private function handle_image_upload($file, $post_id) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $upload_overrides = array('test_form' => false);
        $uploaded_file = wp_handle_upload($file, $upload_overrides);

        if ($uploaded_file && !isset($uploaded_file['error'])) {
            $attachment_id = wp_insert_attachment([
                'post_mime_type' => $uploaded_file['type'],
                'post_title' => preg_replace('/\.[^.]+$/', '', basename($uploaded_file['file'])),
                'post_content' => '',
                'post_status' => 'inherit'
            ], $uploaded_file['file'], $post_id);

            if (!is_wp_error($attachment_id)) {
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                $metadata = wp_generate_attachment_metadata($attachment_id, $uploaded_file['file']);
                wp_update_attachment_metadata($attachment_id, $metadata);
                return $uploaded_file['url'];
            }
        }

        return false;
    }
}
