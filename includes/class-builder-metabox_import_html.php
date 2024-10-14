<?php
class Import_HTML_Block {
    public function render($data, $index) {
        ?>
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
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
                <select name="builder_blocks[<?php echo $index; ?>][data][html_background_type]" class="html_background_type_selector mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="color" <?php selected($data['html_background_type'] ?? 'color', 'color'); ?>>Couleur</option>
                    <option value="image" <?php selected($data['html_background_type'] ?? 'color', 'image'); ?>>Image</option>
                </select>
            </div>

            <!-- Couleur de background -->
            <div class="mb-4 html_bg-color-field <?php echo ($data['html_background_type'] ?? 'color') === 'image' ? 'hidden' : ''; ?>">
                <label class="block text-sm font-medium text-gray-700 mb-2">Couleur de background :</label>
                <input type="color" name="builder_blocks[<?php echo $index; ?>][data][html_background_color]" value="<?php echo esc_attr($data['html_background_color'] ?? '#ffffff'); ?>" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-10 w-20">
            </div>

            <!-- Image de background -->
            <div class="mb-4 html_bg-image-field <?php echo ($data['html_background_type'] ?? 'color') === 'color' ? 'hidden' : ''; ?>">
                <label class="block text-sm font-medium text-gray-700 mb-2">Image de background :</label>
                <input type="file" name="builder_blocks[<?php echo $index; ?>][data][html_background_image]" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <?php if (!empty($data['html_background_image'])) : ?>
                    <div class="relative inline-block">
                        <input type="hidden" name="builder_blocks[<?php echo esc_attr($index); ?>][data][html_background_image_existing]" value="<?php echo esc_url($data['html_background_image']); ?>">
                        <img src="<?php echo esc_url($data['html_background_image']); ?>" alt="Background actuel" class="mt-2 max-w-xs">
                        <!-- Bouton de suppression -->
                        <button type="button" class="absolute top-2 right-0 bg-red-500 text-white p-1 rounded-full alt-remove-img">
                            &times;
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const backgroundTypeSelector = document.querySelector('.html_background_type_selector');
                const colorField = document.querySelector('.html_bg-color-field');
                const imageField = document.querySelector('.html_bg-image-field');

                function toggleBackgroundFields() {
                    if (backgroundTypeSelector.value === 'color') {
                        colorField.classList.remove('hidden');
                        imageField.classList.add('hidden');
                    } else {
                        colorField.classList.add('hidden');
                        imageField.classList.remove('hidden');
                    }
                }

                backgroundTypeSelector.addEventListener('change', toggleBackgroundFields);
                toggleBackgroundFields(); // Initial call
            });
        </script>
        <?php
    }

    public function sanitize($data, $post_id, $index) {
        $sanitized_data = array(
            'html_title' => sanitize_text_field($data['html_title'] ?? ''),
            'html_title_tag' => sanitize_text_field($data['html_title_tag'] ?? 'h2'),
            'html_code' => wp_kses_post($data['html_code'] ?? ''),
            'html_id' => sanitize_text_field($data['html_id'] ?? ''),
            'html_background_type' => sanitize_text_field($data['html_background_type'] ?? 'color'),
            'html_background_color' => sanitize_hex_color($data['html_background_color'] ?? '#ffffff'),
        );

        // Gestion de l'image de background
        if ($data['html_background_type'] === 'image') {
            if (!empty($_FILES['builder_blocks']['name'][$index]['data']['html_background_image'])) {
                $file = array(
                    'name'     => $_FILES['builder_blocks']['name'][$index]['data']['html_background_image'],
                    'type'     => $_FILES['builder_blocks']['type'][$index]['data']['html_background_image'],
                    'tmp_name' => $_FILES['builder_blocks']['tmp_name'][$index]['data']['html_background_image'],
                    'error'    => $_FILES['builder_blocks']['error'][$index]['data']['html_background_image'],
                    'size'     => $_FILES['builder_blocks']['size'][$index]['data']['html_background_image']
                );
                $upload = $this->handle_image_upload($file, $post_id);
                if ($upload && !is_wp_error($upload)) {
                    $sanitized_data['html_background_image'] = $upload['url'];
                }
            } elseif (!empty($data['html_background_image_existing'])) {
                $sanitized_data['html_background_image'] = esc_url_raw($data['html_background_image_existing']);
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
