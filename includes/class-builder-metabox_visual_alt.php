<?php
class Alternate_Visual_Block {
    public function render($data, $index) {
        ?>
        <div class="alternate-visual-block bg-white shadow-md rounded-lg p-6 mb-6 builder-block">
            <!-- Titre -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Titre :</label>
                <input type="text" name="builder_blocks[<?php echo $index; ?>][data][av_title]" value="<?php echo esc_attr($data['av_title'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <!-- Type de balise titre -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de balise titre :</label>
                <select name="builder_blocks[<?php echo $index; ?>][data][av_title_tag]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <?php
                    $title_tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
                    foreach ($title_tags as $tag) {
                        echo '<option value="' . esc_attr($tag) . '"' . selected($data['av_title_tag'] ?? 'h2', $tag, false) . '>' . esc_html($tag) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <!-- Sous-titre -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Sous-titre :</label>
                <input type="text" name="builder_blocks[<?php echo $index; ?>][data][av_subtitle]" value="<?php echo esc_attr($data['av_subtitle'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <!-- Paragraphe -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Paragraphe :</label>
                <textarea name="builder_blocks[<?php echo $index; ?>][data][av_paragraph]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" rows="4"><?php echo esc_textarea($data['av_paragraph'] ?? ''); ?></textarea>
            </div>

            <!-- Taille de police -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Taille de police du paragraphe :</label>
                <input type="number" name="builder_blocks[<?php echo $index; ?>][data][av_font_size]" value="<?php echo esc_attr($data['av_font_size'] ?? '16'); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <!-- Couleur du texte -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Couleur du texte du paragraphe :</label>
                <input type="color" name="builder_blocks[<?php echo $index; ?>][data][av_paragraph_color]" value="<?php echo esc_attr($data['av_paragraph_color'] ?? '#000000'); ?>" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-10 w-20">
            </div>

            <!-- Image à l'intérieur du block -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Image à l'intérieur du block :</label>
                <button type="button" class="av-inner-image-selector bg-blue-500 text-white py-2 px-4 rounded mb-2">Ajouter ou sélectionner une image</button>
                <input type="hidden" name="builder_blocks[<?php echo esc_attr($index); ?>][data][av_inner_image]" value="<?php echo esc_url($data['av_inner_image'] ?? ''); ?>" class="av-inner-image-url">
                <div class="mt-4 av-inner-image-container <?php echo empty($data['av_inner_image']) ? 'hidden' : ''; ?>">
                    <img src="<?php echo esc_url($data['av_inner_image']); ?>" alt="Image intérieure" class="av-inner-image-preview">
                    <button type="button" class="absolute top-0 right-0 bg-red-500 text-white p-1 rounded-full remove-av-inner-image">&times;</button>
                </div>
            </div>

            <!-- Alignement de l'image -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Alignement de l'image :</label>
                <select name="builder_blocks[<?php echo $index; ?>][data][av_image_alignment]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="left" <?php selected($data['av_image_alignment'] ?? 'left', 'left'); ?>>Gauche</option>
                    <option value="right" <?php selected($data['av_image_alignment'] ?? 'left', 'right'); ?>>Droite</option>
                </select>
            </div>

            <!-- Type de background -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de background :</label>
                <select name="builder_blocks[<?php echo $index; ?>][data][av_background_type]" class="av-background-type-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="color" <?php selected($data['av_background_type'] ?? 'color', 'color'); ?>>Couleur</option>
                    <option value="image" <?php selected($data['av_background_type'] ?? 'color', 'image'); ?>>Image</option>
                </select>
            </div>

            <!-- Couleur de background -->
            <div class="mb-4 av-bg-color-field <?php echo ($data['av_background_type'] ?? 'color') === 'image' ? 'hidden' : ''; ?>">
                <label class="block text-sm font-medium text-gray-700 mb-2">Couleur de background :</label>
                <input type="color" name="builder_blocks[<?php echo $index; ?>][data][av_background_value]" value="<?php echo esc_attr($data['av_background_value'] ?? '#ffffff'); ?>" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-10 w-20">
            </div>

            <!-- Image de background -->
            <div class="mb-4 av-bg-image-field <?php echo ($data['av_background_type'] ?? 'color') === 'color' ? 'hidden' : ''; ?>">
                <label class="block text-sm font-medium text-gray-700 mb-2">Image de background :</label>
                <button type="button" class="av-bg-image-selector bg-blue-500 text-white py-2 px-4 rounded mb-2">Ajouter ou sélectionner une image</button>
                <input type="hidden" name="builder_blocks[<?php echo esc_attr($index); ?>][data][av_background_value]" value="<?php echo esc_url($data['av_background_value'] ?? ''); ?>" class="av-bg-image-url">
                <div class="mt-4 av-bg-image-container <?php echo empty($data['av_background_value']) ? 'hidden' : ''; ?>">
                    <img src="<?php echo esc_url($data['av_background_value']); ?>" alt="Image de background" class="av-bg-image-preview max-w-xs">
                    <button type="button" class="absolute top-0 right-0 bg-red-500 text-white p-1 rounded-full remove-av-bg-image">&times;</button>
                </div>
            </div>

            <!-- Affichage du bouton -->
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" class="av_show_button_checkbox" name="builder_blocks[<?php echo $index; ?>][data][av_show_button]" value="1" <?php checked(isset($data['av_show_button']) && $data['av_show_button'] == 1); ?>>
                    <span class="ml-2 text-sm text-gray-700">Ajouter un bouton</span>
                </label>
            </div>

            <!-- Options du bouton -->
            <div class="av_button-options <?php echo (isset($data['av_show_button']) && $data['av_show_button'] == 1) ? 'block' : 'hidden'; ?> bg-gray-50 p-4 rounded-md">
                <p class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Texte du bouton :</label>
                    <input type="text" name="builder_blocks[<?php echo $index; ?>][data][av_button_text]" value="<?php echo esc_attr($data['av_button_text'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </p>
                <p class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lien du bouton :</label>
                    <input type="url" name="builder_blocks[<?php echo $index; ?>][data][av_button_link]" value="<?php echo esc_url($data['av_button_link'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </p>
                <p class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Couleur du bouton :</label>
                    <input type="color" name="builder_blocks[<?php echo $index; ?>][data][av_button_color]" value="<?php echo esc_attr($data['av_button_color'] ?? '#000000'); ?>" class="mt-1 block h-10 w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </p>
            </div>
        </div>


        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const showButtonCheckbox = document.querySelector('.av_show_button_checkbox');
                const buttonOptions = document.querySelector('.button-options');

                function toggleButtonOptions() {
                    if (showButtonCheckbox.checked) {
                        buttonOptions.classList.remove('hidden');
                        buttonOptions.classList.add('block');
                    } else {
                        buttonOptions.classList.add('hidden');
                        buttonOptions.classList.remove('block');
                    }
                }

                showButtonCheckbox.addEventListener('change', toggleButtonOptions);
                toggleButtonOptions();
            });
        </script>
        <?php
    }

    public function sanitize($data, $post_id, $index) {
        // var_dump($data);
        // die();
        $sanitized_data = array(
            'av_title' => isset($data['av_title']) ? sanitize_text_field($data['av_title']) : '',
            'av_title_tag' => isset($data['av_title_tag']) ? sanitize_text_field($data['av_title_tag']) : 'h2',
            'av_subtitle' => isset($data['av_subtitle']) ? sanitize_text_field($data['av_subtitle']) : '',
            'av_paragraph' => isset($data['av_paragraph']) ? wp_kses_post($data['av_paragraph']) : '',
            'av_font_size' => isset($data['av_font_size']) ? intval($data['av_font_size']) : 16,
            'av_paragraph_color' => isset($data['av_paragraph_color']) ? sanitize_hex_color($data['av_paragraph_color']) : '#000000',
            'av_background_type' => isset($data['av_background_type']) ? sanitize_text_field($data['av_background_type']) : 'color',
            'av_background_value' => isset($data['av_background_value']) ? sanitize_text_field($data['av_background_value']) : '#ffffff',
            'av_show_button' => isset($data['av_show_button']) ? 1 : 0,
            'av_button_text' => isset($data['av_button_text']) ? sanitize_text_field($data['av_button_text']) : '',
            'av_button_link' => isset($data['av_button_link']) ? esc_url_raw($data['av_button_link']) : '',
            'av_button_color' => isset($data['av_button_color']) ? sanitize_hex_color($data['av_button_color']) : '#000000',
            'av_image_alignment' => isset($data['av_image_alignment']) ? sanitize_text_field($data['av_image_alignment']) : 'left', // Traitement de l'alignement de l'image
        );

        // Gestion de l'image/vidéo/GIF uploadé
        // if (!empty($_FILES['builder_blocks']['name'][$index]['data']['av_file'])) {
        //     $file = array(
        //         'name'     => $_FILES['builder_blocks']['name'][$index]['data']['av_file'],
        //         'type'     => $_FILES['builder_blocks']['type'][$index]['data']['av_file'],
        //         'tmp_name' => $_FILES['builder_blocks']['tmp_name'][$index]['data']['av_file'],
        //         'error'    => $_FILES['builder_blocks']['error'][$index]['data']['av_file'],
        //         'size'     => $_FILES['builder_blocks']['size'][$index]['data']['av_file']
        //     );
        //     $upload = $this->handle_image_upload($file, $post_id);
        //     if ($upload && !is_wp_error($upload)) {
        //         $sanitized_data['av_file'] = $upload['url'];
        //     }
        // } elseif (!empty($data['av_file'])) {
        //     $sanitized_data['av_file'] = esc_url_raw($data['av_file']);
        // }

    // Gestion de l'image de background
    if ($data['av_background_type'] === 'image') {
        if (!empty($data['av_background_value'])) {
            $sanitized_data['av_background_value'] = esc_url_raw($data['av_background_value']);
        }
    }

    // Gestion de l'image intérieure du block
    if (!empty($data['av_inner_image'])) {
        $sanitized_data['av_inner_image'] = esc_url_raw($data['av_inner_image']);
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
