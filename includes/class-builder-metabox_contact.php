<?php
class Contact_Block {
    public function render($data, $index) {
        ?>
        <div class="contact-block bg-white shadow-md rounded-lg p-6 mb-6">
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

            <!-- Description -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description :</label>
                <textarea name="builder_blocks[<?php echo $index; ?>][data][description]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" rows="4"><?php echo esc_textarea($data['description'] ?? ''); ?></textarea>
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
                <input type="file" name="builder_blocks[<?php echo $index; ?>][data][bg_image]" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <?php if (!empty($data['bg_image'])) : ?>
                    <img src="<?php echo esc_url($data['bg_image']); ?>" alt="Background actuel" class="mt-2 max-w-xs">
                <?php endif; ?>
            </div>

            <!-- Code Salesforce -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Code Salesforce (embed) :</label>
                <textarea name="builder_blocks[<?php echo $index; ?>][data][salesforce_code]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" rows="4"><?php echo esc_textarea($data['salesforce_code'] ?? ''); ?></textarea>
            </div>
        </div>
        <?php
    }

    public function sanitize($data, $post_id, $index) {
        $sanitized_data = array(
            'title' => isset($data['title']) ? sanitize_text_field($data['title']) : '',
            'title_tag' => isset($data['title_tag']) ? sanitize_text_field($data['title_tag']) : 'h2',
            'description' => isset($data['description']) ? wp_kses_post($data['description']) : '',
            'bg_type' => isset($data['bg_type']) ? sanitize_text_field($data['bg_type']) : 'color',
            'bg_color' => isset($data['bg_color']) ? sanitize_hex_color($data['bg_color']) : '#ffffff',
            'salesforce_code' => isset($data['salesforce_code']) ? wp_kses_post($data['salesforce_code']) : ''
        );

        // Gestion de l'image de background
        if ($data['bg_type'] === 'image') {
            if (!empty($_FILES['builder_blocks']['name'][$index]['data']['bg_image'])) {
                $file = array(
                    'name'     => $_FILES['builder_blocks']['name'][$index]['data']['bg_image'],
                    'type'     => $_FILES['builder_blocks']['type'][$index]['data']['bg_image'],
                    'tmp_name' => $_FILES['builder_blocks']['tmp_name'][$index]['data']['bg_image'],
                    'error'    => $_FILES['builder_blocks']['error'][$index]['data']['bg_image'],
                    'size'     => $_FILES['builder_blocks']['size'][$index]['data']['bg_image']
                );
                $upload = $this->handle_image_upload($file, $post_id);
                if ($upload && !is_wp_error($upload)) {
                    $sanitized_data['bg_image'] = $upload['url'];
                }
            } elseif (!empty($data['bg_image'])) {
                $sanitized_data['bg_image'] = esc_url_raw($data['bg_image']);
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
