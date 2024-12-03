<?php
class Client_Carousel_Block {
    public function render($data, $index) {
        ?>
        <div class="client-carousel-block bg-white shadow-md rounded-lg p-6 mb-6 builder-block">
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

            <!-- Upload de logos -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Uploader les logos (jusqu'à 8 fichiers) :</label>
                <!-- Bouton avec data-name pour indiquer où les données seront injectées -->
                <button type="button" 
                    class="lc-logo-selector bg-blue-500 text-white py-2 px-4 rounded mb-2" 
                    data-name="builder_blocks[<?php echo esc_attr($index); ?>][data][lc_logos]">
                    Ajouter ou sélectionner des logos
                </button>

                <!-- Conteneur pour les logos -->
                <div class="mt-4 grid grid-cols-4 gap-4 lc-logos-container">
                    <?php if (!empty($data['lc_logos']) && is_array($data['lc_logos'])) : ?>
                        <?php foreach ($data['lc_logos'] as $logo) : ?>
                            <div class="relative lc-logo-item">
                                <img src="<?php echo esc_url($logo); ?>" alt="Logo" class="max-w-full h-auto">
                                <button type="button" class="absolute top-0 right-0 bg-red-500 text-white p-1 rounded-full lc-remove-logo">
                                    &times;
                                </button>
                                <input type="hidden" 
                                    name="builder_blocks[<?php echo esc_attr($index); ?>][data][lc_logos][]" 
                                    value="<?php echo esc_url($logo); ?>" 
                                    data-name="builder_blocks[<?php echo esc_attr($index); ?>][data][lc_logos]">
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <!-- Placeholder invisible pour garantir que data-name existe dès le début -->
                        <input type="hidden" 
                            name="builder_blocks[<?php echo esc_attr($index); ?>][data][lc_logos][]" 
                            value="" 
                            data-name="builder_blocks[<?php echo esc_attr($index); ?>][data][lc_logos]">
                    <?php endif; ?>
                </div>
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
                <button type="button" class="lc-bg-image-selector bg-blue-500 text-white py-2 px-4 rounded mb-2">Ajouter ou sélectionner une image</button>
                <input type="hidden" name="builder_blocks[<?php echo esc_attr($index); ?>][data][lc_background_image]" value="<?php echo esc_url($data['lc_background_image'] ?? ''); ?>" class="lc-bg-image-url">
                <div class="mt-4 lc-bg-image-container <?php echo empty($data['lc_background_image']) ? 'hidden' : ''; ?>">
                    <img src="<?php echo esc_url($data['lc_background_image']); ?>" alt="Background actuel" class="lc-bg-image-preview max-w-xs">
                    <button type="button" class="bg-red-500 text-white p-1 rounded-full lc-remove-bg-image">&times;</button>
                </div>
            </div>
        </div>
        <?php
    }

    public function sanitize($data, $post_id, $index) {
        $sanitized_data = [
            'lc_title' => sanitize_text_field($data['lc_title'] ?? ''),
            'lc_title_tag' => sanitize_text_field($data['lc_title_tag'] ?? 'h2'),
            'lc_black_white' => isset($data['lc_black_white']) ? 1 : 0,
            'lc_background_type' => sanitize_text_field($data['lc_background_type'] ?? 'color'),
            'lc_background_color' => sanitize_hex_color($data['lc_background_color'] ?? '#ffffff'),
        ];

        // Gestion des logos
        $sanitized_data['lc_logos'] = [];
        if (!empty($data['lc_logos'])) {
            $logos = is_array($data['lc_logos']) ? $data['lc_logos'] : json_decode(stripslashes($data['lc_logos']), true);
            if (is_array($logos)) {
                foreach ($logos as $logo) {
                    if (!empty($logo)) { // Ignore les entrées vides
                        $sanitized_data['lc_logos'][] = esc_url_raw($logo);
                    }
                }
            }
        }

        // Gestion de l'image de background
        if ($sanitized_data['lc_background_type'] === 'image') {
            $sanitized_data['lc_background_image'] = esc_url_raw($data['lc_background_image'] ?? '');
        }
        // var_dump($sanitized_data);
        // die();
        return $sanitized_data;
    }

    private function handle_image_upload($file, $post_id) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $upload_overrides = ['test_form' => false];
        $uploaded_file = wp_handle_upload($file, $upload_overrides);

        if ($uploaded_file && !isset($uploaded_file['error'])) {
            $file_name = basename($uploaded_file['file']);
            $file_type = wp_check_filetype($file_name);

            $attachment_data = [
                'post_mime_type' => $file_type['type'],
                'post_title' => preg_replace('/\.[^.]+$/', '', $file_name),
                'post_content' => '',
                'post_status' => 'inherit',
            ];

            $attachment_id = wp_insert_attachment($attachment_data, $uploaded_file['file'], $post_id);

            if (!is_wp_error($attachment_id)) {
                $attachment_metadata = wp_generate_attachment_metadata($attachment_id, $uploaded_file['file']);
                wp_update_attachment_metadata($attachment_id, $attachment_metadata);

                return ['id' => $attachment_id, 'url' => $uploaded_file['url']];
            }
        }

        return false;
    }
}
