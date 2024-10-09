<?php
class Client_Carousel_Block {
    public function render($data, $index) {
        ?>
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <!-- Titre -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Titre :</label>
                <input type="text" name="builder_blocks[<?php echo $index; ?>][data][lc_title]" value="<?php echo esc_attr($data['lc_title'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <!-- Type de balise titre -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de balise titre :</label>
                <select name="builder_blocks[<?php echo $index; ?>][data][lc_title_tag]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <?php
                    $title_tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
                    foreach ($title_tags as $tag) {
                        echo '<option value="' . $tag . '"' . selected($data['lc_title_tag'] ?? 'h2', $tag, false) . '>' . $tag . '</option>';
                    }
                    ?>
                </select>
            </div>

            <!-- Upload des logos (jusqu'à 8) -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Uploader les logos (jusqu'à 8 fichiers) :</label>
                <input type="file" name="builder_blocks[<?php echo esc_attr($index); ?>][data][lc_logos][]" accept="image/*" multiple class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                
                <?php if (!empty($data['lc_logos']) && is_array($data['lc_logos'])) : ?>
                    <div class="mt-4 grid grid-cols-4 gap-4 lc_logos_block">
                        <?php foreach ($data['lc_logos'] as $logo_index => $logo) : ?>
                            <div class="relative">
                                <img src="<?php echo esc_url($logo); ?>" alt="Logo" class="max-w-full h-auto">
                                <!-- Bouton de suppression -->
                                <button type="button" class="absolute top-0 right-0 bg-red-500 text-white p-1 rounded-full lc-remove-logo" data-logo-index="<?php echo esc_attr($logo_index); ?>">
                                    &times;
                                </button>
                                <!-- Champ caché pour conserver le logo existant -->
                                <input type="hidden" name="builder_blocks[<?php echo esc_attr($index); ?>][data][lc_logos_existing][<?php echo esc_attr($logo_index); ?>]" value="<?php echo esc_url($logo); ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>


            <!-- Option noir & blanc -->
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="builder_blocks[<?php echo $index; ?>][data][lc_black_white]" value="1" <?php checked(isset($data['lc_black_white']) && $data['lc_black_white'] == 1); ?> class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Activer le filtre noir & blanc</span>
                </label>
            </div>

            <!-- Type de background -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de background :</label>
                <select name="builder_blocks[<?php echo $index; ?>][data][lc_background_type]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 lc_background_type_selector">
                    <option value="color" <?php selected($data['lc_background_type'] ?? 'color', 'color'); ?>>Couleur</option>
                    <option value="image" <?php selected($data['lc_background_type'] ?? 'color', 'image'); ?>>Image</option>
                </select>
            </div>

            <!-- Couleur de background -->
            <div class="mb-4 lc_bg_color_field <?php echo ($data['lc_background_type'] ?? 'color') === 'image' ? 'hidden' : ''; ?>">
                <label class="block text-sm font-medium text-gray-700 mb-2">Couleur de background :</label>
                <input type="color" name="builder_blocks[<?php echo $index; ?>][data][lc_background_color]" value="<?php echo esc_attr($data['lc_background_color'] ?? '#ffffff'); ?>" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-10 w-20">
            </div>

            <!-- Image de background -->
            <div class="mb-4 lc_bg_image_field <?php echo ($data['lc_background_type'] ?? 'color') === 'color' ? 'hidden' : ''; ?>">
                <label class="block text-sm font-medium text-gray-700 mb-2">Image de background :</label>
                <input type="file" name="builder_blocks[<?php echo $index; ?>][data][lc_background_image]" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <?php if (!empty($data['lc_background_image'])) : ?>
                    <img src="<?php echo esc_url($data['lc_background_image']); ?>" alt="Background actuel" class="mt-2 max-w-xs">
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    public function sanitize($data, $post_id, $index) {
    $sanitized_data = array(
        'lc_title' => isset($data['lc_title']) ? sanitize_text_field($data['lc_title']) : '',
        'lc_title_tag' => isset($data['lc_title_tag']) ? sanitize_text_field($data['lc_title_tag']) : 'h2',
        'lc_black_white' => isset($data['lc_black_white']) ? 1 : 0,
        'lc_background_type' => isset($data['lc_background_type']) ? sanitize_text_field($data['lc_background_type']) : 'color',
        'lc_background_color' => isset($data['lc_background_color']) ? sanitize_hex_color($data['lc_background_color']) : '#ffffff',
    );

    // Gestion des logos (jusqu'à 8 fichiers)
    $sanitized_data['lc_logos'] = array();

    // Prendre en compte les logos existants (qui n'ont pas été supprimés)
    if (!empty($data['lc_logos_existing'])) {
        foreach ($data['lc_logos_existing'] as $existing_logo) {
            if (!empty($existing_logo)) {
                $sanitized_data['lc_logos'][] = esc_url_raw($existing_logo);
            }
        }
    }

    // Gestion des nouveaux uploads
    if (!empty($_FILES['builder_blocks']['name'][$index]['data']['lc_logos'])) {
        for ($i = 0; $i < count($_FILES['builder_blocks']['name'][$index]['data']['lc_logos']); $i++) {
            if (!empty($_FILES['builder_blocks']['name'][$index]['data']['lc_logos'][$i])) {
                $file = array(
                    'name'     => $_FILES['builder_blocks']['name'][$index]['data']['lc_logos'][$i],
                    'type'     => $_FILES['builder_blocks']['type'][$index]['data']['lc_logos'][$i],
                    'tmp_name' => $_FILES['builder_blocks']['tmp_name'][$index]['data']['lc_logos'][$i],
                    'error'    => $_FILES['builder_blocks']['error'][$index]['data']['lc_logos'][$i],
                    'size'     => $_FILES['builder_blocks']['size'][$index]['data']['lc_logos'][$i],
                );
                $upload = $this->handle_image_upload($file, $post_id);
                if ($upload && !is_wp_error($upload)) {
                    $sanitized_data['lc_logos'][] = $upload['url'];
                }
            }
        }
    }

    // Gestion de l'image de background
    if ($data['lc_background_type'] === 'image') {
        if (!empty($_FILES['builder_blocks']['name'][$index]['data']['lc_background_image'])) {
            $file = array(
                'name'     => $_FILES['builder_blocks']['name'][$index]['data']['lc_background_image'],
                'type'     => $_FILES['builder_blocks']['type'][$index]['data']['lc_background_image'],
                'tmp_name' => $_FILES['builder_blocks']['tmp_name'][$index]['data']['lc_background_image'],
                'error'    => $_FILES['builder_blocks']['error'][$index]['data']['lc_background_image'],
                'size'     => $_FILES['builder_blocks']['size'][$index]['data']['lc_background_image']
            );
            $upload = $this->handle_image_upload($file, $post_id);
            if ($upload && !is_wp_error($upload)) {
                $sanitized_data['lc_background_image'] = $upload['url'];
            }
        } elseif (!empty($data['lc_background_image'])) {
            $sanitized_data['lc_background_image'] = esc_url_raw($data['lc_background_image']);
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
