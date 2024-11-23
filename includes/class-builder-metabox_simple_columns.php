<?php
class Simple_Columns_Block {
    public function render($data, $index) {
        for ($col = 0; $col < 3; $col++) {
            ?>
            <div class="simple-columns-field simple-columns-block bg-white shadow-md rounded-lg p-6 mb-6">
                <!-- Titre -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Titre (Colonne <?php echo $col + 1; ?>) :</label>
                    <input type="text" name="builder_blocks[<?php echo $index; ?>][data][columns][<?php echo $col; ?>][title]" value="<?php echo esc_attr($data['columns'][$col]['title'] ?? ''); ?>" class="simple-columns-title-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <!-- Type de titre -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type de titre (Colonne <?php echo $col + 1; ?>) :</label>
                    <select name="builder_blocks[<?php echo $index; ?>][data][columns][<?php echo $col; ?>][title_tag]" class="simple-columns-title-tag mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <?php
                        $title_tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
                        foreach ($title_tags as $tag) {
                            echo '<option value="' . $tag . '"' . selected($data['columns'][$col]['title_tag'] ?? 'h2', $tag, false) . '>' . $tag . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description (Colonne <?php echo $col + 1; ?>) :</label>
                    <textarea name="builder_blocks[<?php echo $index; ?>][data][columns][<?php echo $col; ?>][description]" class="simple-columns-description mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" rows="4"><?php echo esc_textarea($data['columns'][$col]['description'] ?? ''); ?></textarea>
                </div>

                <!-- Image -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Image (Colonne <?php echo $col + 1; ?>) :</label>
                    <button type="button" class="extra-image-selector bg-blue-500 text-white py-2 px-4 rounded mb-2">Ajouter ou sélectionner une image</button>
                    <input type="hidden" name="builder_blocks[<?php echo $index; ?>][data][columns][<?php echo $col; ?>][image]" value="<?php echo esc_url($data['columns'][$col]['image'] ?? ''); ?>" class="extra-image-url">
                    <div class="extra-image-container <?php echo empty($data['columns'][$col]['image']) ? 'hidden' : ''; ?>">
                        <img src="<?php echo esc_url($data['columns'][$col]['image']); ?>" alt="Image colonne <?php echo $col + 1; ?>" class="extra-image-preview max-w-xs">
                        <button type="button" class="remove-extra-image bg-red-500 text-white p-1 rounded-full">&times;</button>
                    </div>
                </div>

                <!-- Type de background -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type de background (Colonne <?php echo $col + 1; ?>) :</label>
                    <select name="builder_blocks[<?php echo $index; ?>][data][columns][<?php echo $col; ?>][background_type]" class="bg-type-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="color" <?php selected($data['columns'][$col]['background_type'] ?? 'color', 'color'); ?>>Couleur</option>
                        <option value="image" <?php selected($data['columns'][$col]['background_type'] ?? 'color', 'image'); ?>>Image</option>
                    </select>
                </div>

                <!-- Couleur de background -->
                <div class="mb-4 bg-color-field <?php echo ($data['columns'][$col]['background_type'] ?? 'color') === 'image' ? 'hidden' : ''; ?>">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Couleur de background :</label>
                    <input type="color" name="builder_blocks[<?php echo $index; ?>][data][columns][<?php echo $col; ?>][background_color]" value="<?php echo esc_attr($data['columns'][$col]['background_color'] ?? '#ffffff'); ?>" class="bg-color-input mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-10 w-20">
                </div>

                <!-- Image de background -->
                <div class="mb-4 bg-image-field <?php echo ($data['columns'][$col]['background_type'] ?? 'color') === 'color' ? 'hidden' : ''; ?>">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Image de background :</label>
                    <button type="button" class="bg-image-selector bg-blue-500 text-white py-2 px-4 rounded mb-2">Ajouter ou sélectionner une image</button>
                    <input type="hidden" name="builder_blocks[<?php echo $index; ?>][data][columns][<?php echo $col; ?>][background_image]" value="<?php echo esc_url($data['columns'][$col]['background_image'] ?? ''); ?>" class="bg-image-url">
                    <div class="bg-image-container <?php echo empty($data['columns'][$col]['background_image']) ? 'hidden' : ''; ?>">
                        <img src="<?php echo esc_url($data['columns'][$col]['background_image']); ?>" alt="Background colonne <?php echo $col + 1; ?>" class="bg-image-preview max-w-xs">
                        <button type="button" class="remove-bg-image bg-red-500 text-white p-1 rounded-full">&times;</button>
                    </div>
                </div>
            </div>
            <?php
        }
    }

    public function sanitize($data, $post_id, $index) {
        $sanitized_data = array();

        for ($col = 0; $col < 3; $col++) {
            $sanitized_column = array(
                'title' => isset($data['columns'][$col]['title']) ? sanitize_text_field($data['columns'][$col]['title']) : '',
                'title_tag' => isset($data['columns'][$col]['title_tag']) ? sanitize_text_field($data['columns'][$col]['title_tag']) : 'h2',
                'description' => isset($data['columns'][$col]['description']) ? wp_kses_post($data['columns'][$col]['description']) : '',
                'background_type' => isset($data['columns'][$col]['background_type']) ? sanitize_text_field($data['columns'][$col]['background_type']) : 'color',
            );

            if (!empty($data['columns'][$col]['image'])) {
                $sanitized_column['image'] = esc_url_raw($data['columns'][$col]['image']);
            }

            if ($sanitized_column['background_type'] === 'image') {
                if (!empty($data['columns'][$col]['background_image'])) {
                    $sanitized_column['background_image'] = esc_url_raw($data['columns'][$col]['background_image']);
                }
            } else {
                $sanitized_column['background_color'] = isset($data['columns'][$col]['background_color']) ? sanitize_hex_color($data['columns'][$col]['background_color']) : '#ffffff';
            }

            $sanitized_data['columns'][$col] = $sanitized_column;
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
