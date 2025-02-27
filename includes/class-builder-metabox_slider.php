<?php
class Slider_Block {
    public function render($data, $index) {
        ?>
        <div class="slider-block bg-white shadow-md rounded-lg p-6 mb-6">
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">Configuration du Slider</h3>
                <div class="slider-slides" data-block-index="<?php echo $index; ?>">
                    <?php
                    $slides = $data['slides'] ?? [[]];
                    foreach ($slides as $slide_index => $slide) {
                        $this->render_slide_fields($index, $slide_index, $slide);
                    }
                    ?>
                </div>
                <button type="button" class="add-slide mt-4 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded" <?php echo (count($slides) >= 4) ? 'disabled' : ''; ?>>
                    Ajouter un slide
                </button>
            </div>

            <!-- ID du block -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">ID du block :</label>
                <input type="text" name="builder_blocks[<?php echo $index; ?>][data][block_id]" value="<?php echo esc_attr($data['block_id'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <p class="text-sm text-gray-500 mt-1">Entrez un identifiant unique pour ce block (lettres, chiffres, tirets uniquement).</p>
            </div>
        </div>

        <script type="text/template" id="slide-template">
            <?php $this->render_slide_fields('BLOCK_INDEX', 'SLIDE_INDEX', []); ?>
        </script>
        <?php
    }

    private function render_slide_fields($block_index, $slide_index, $slide_data) {
        $slide_index = intval($slide_index);
        ?>
        <div class="slide-fields bg-gray-100 p-4 rounded-md mb-4">
            <h4 class="font-semibold mb-2">Slide <span class="slide-number"><?php echo esc_html($slide_index + 1); ?></span></h4>

            <!-- Titre du slide -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Titre du slide :</label>
                <input type="text" name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][slides][<?php echo esc_attr($slide_index); ?>][title]" value="<?php echo esc_attr($slide_data['title'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <!-- Type de titre -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de titre :</label>
                <select name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][slides][<?php echo esc_attr($slide_index); ?>][title_tag]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <?php
                    $title_tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
                    foreach ($title_tags as $tag) {
                        printf(
                            '<option value="%s"%s>%s</option>',
                            esc_attr($tag),
                            selected($slide_data['title_tag'] ?? 'h2', $tag, false),
                            esc_html($tag)
                        );
                    }
                    ?>
                </select>
            </div>

            <!-- Type de background -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de background :</label>
                <select name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][slides][<?php echo esc_attr($slide_index); ?>][bg_type]" class="bg-type-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="color" <?php selected($slide_data['bg_type'] ?? 'color', 'color'); ?>>Couleur</option>
                    <option value="image" <?php selected($slide_data['bg_type'] ?? 'color', 'image'); ?>>Image</option>
                </select>
            </div>

            <!-- Couleur de background -->
            <div class="mb-4 bg-color-field <?php echo ($slide_data['bg_type'] ?? '') === 'image' ? 'hidden' : ''; ?>">
                <label class="block text-sm font-medium text-gray-700 mb-2">Couleur de background :</label>
                <input type="color" name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][slides][<?php echo esc_attr($slide_index); ?>][bg_color]" value="<?php echo esc_attr($slide_data['bg_color'] ?? '#ffffff'); ?>" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-10 w-20">
            </div>

            <!-- Image de background -->
            <div class="mb-4 bg-image-field <?php echo ($slide_data['bg_type'] ?? '') === 'color' ? 'hidden' : ''; ?>">
                <label class="block text-sm font-medium text-gray-700 mb-2">Image de background :</label>
                <button type="button" class="slide-bg-image-selector bg-blue-500 text-white py-2 px-4 rounded mb-2">Ajouter ou sélectionner une image</button>
                <input type="hidden" name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][slides][<?php echo esc_attr($slide_index); ?>][bg_image]" value="<?php echo esc_url($slide_data['bg_image'] ?? ''); ?>" class="slide-bg-image-url">
                <div class="mt-4 slide-bg-image-container <?php echo empty($slide_data['bg_image']) ? 'hidden' : ''; ?>">
                    <img src="<?php echo !empty($slide_data['bg_image']) ? esc_url($slide_data['bg_image']) : ''; ?>" alt="Background actuel" class="slide-bg-image-preview max-w-xs">
                    <button type="button" class="bg-red-500 text-white p-1 rounded-full slide-remove-bg-image">&times;</button>
                </div>
            </div>

            <!-- Type de média -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de média :</label>
                <select name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][slides][<?php echo esc_attr($slide_index); ?>][media_type]" class="media-type-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="image" <?php selected($slide_data['media_type'] ?? 'image', 'image'); ?>>Image</option>
                    <option value="video" <?php selected($slide_data['media_type'] ?? 'image', 'video'); ?>>Vidéo</option>
                </select>
            </div>

            <!-- Fichier de média -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Image supplémentaire :</label>
                <button type="button" class="slide-image-selector bg-blue-500 text-white py-2 px-4 rounded mb-2">Ajouter ou sélectionner une image</button>
                <input type="hidden" name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][slides][<?php echo esc_attr($slide_index); ?>][media_file]" value="<?php echo esc_url($slide_data['media_file'] ?? ''); ?>" class="slide-image-url">
                <div class="mt-4 slide-image-container <?php echo empty($slide_data['media_file']) ? 'hidden' : ''; ?>">
                    <img src="<?php echo !empty($slide_data['media_file']) ? esc_url($slide_data['media_file']) : ''; ?>" alt="Background actuel" class="slide-image-preview max-w-xs">
                    <button type="button" class="bg-red-500 text-white p-1 rounded-full slide-remove-image">&times;</button>
                </div>
            </div>

            <p class="mb-4">
                <label class="inline-flex items-center">
                <input type="checkbox" class="show_button_checkbox" data-index="<?php echo $slide_index; ?>" name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][slides][<?php echo esc_attr($slide_index); ?>][show_button]" value="1" <?php checked(isset($slide_data['show_button']) && $slide_data['show_button'] == 1); ?> class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">


                    <span class="ml-2 text-sm text-gray-700">Ajouter un bouton</span>
                </label>
            </p>
            <div class="button-options <?php echo (isset($slide_data['show_button']) && $slide_data['show_button'] == 1) ? 'block' : 'hidden'; ?> bg-gray-50 p-4 rounded-md" data-index="<?php echo $slide_index; ?>">


                <p class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Texte du bouton :</label>
                    <input type="text" name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][slides][<?php echo esc_attr($slide_index); ?>][button_text]" value="<?php echo esc_attr($slide_data['button_text'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </p>
                <p class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lien du bouton :</label>
                    <input type="url" name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][slides][<?php echo esc_attr($slide_index); ?>][button_link]" value="<?php echo esc_url($slide_data['button_link'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </p>
                <p class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Couleur du bouton :</label>
                    <input type="color" name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][slides][<?php echo esc_attr($slide_index); ?>][button_color]" value="<?php echo esc_attr($slide_data['button_color'] ?? '#000000'); ?>" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-10 w-20">
                </p>
            </div>            

            <button type="button" class="remove-slide mt-4 bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                Supprimer ce slide
            </button>
        </div>
        <?php
    }

    public function sanitize($data, $post_id, $index) {
        $sanitized_data = array();

        if (isset($data['slides']) && is_array($data['slides'])) {
            foreach ($data['slides'] as $slide_index => $slide_data) {
                $sanitized_slide = array(
                    'title'       => isset($slide_data['title']) ? sanitize_text_field($slide_data['title']) : '',
                    'title_tag'   => isset($slide_data['title_tag']) ? sanitize_text_field($slide_data['title_tag']) : 'h2',
                    'bg_type'     => isset($slide_data['bg_type']) ? sanitize_text_field($slide_data['bg_type']) : 'color',
                    'bg_color'    => isset($slide_data['bg_color']) ? sanitize_hex_color($slide_data['bg_color']) : '#ffffff',
                    'media_type'  => isset($slide_data['media_type']) ? sanitize_text_field($slide_data['media_type']) : 'image',
                    'show_button' => isset($slide_data['show_button']) ? sanitize_text_field($slide_data['show_button']) : '',
                    'button_text' => isset($slide_data['button_text']) ? sanitize_text_field($slide_data['button_text']) : '',
                    'button_link' => isset($slide_data['button_link']) ? esc_url_raw($slide_data['button_link']) : '',
                    'button_color' => isset($slide_data['button_color']) ? sanitize_hex_color($slide_data['button_color']) : '#000000'                    
                );              


                // Gestion de l'image de background
                if (isset($slide_data['bg_image']) && !empty($slide_data['bg_image'])) {
                    $sanitized_slide['bg_image'] = esc_url_raw($slide_data['bg_image']);
                }

                // Gestion de l'image supplémentaire
                if (isset($slide_data['media_file']) && !empty($slide_data['media_file'])) {
                    $sanitized_slide['media_file'] = esc_url_raw($slide_data['media_file']);
                }

                $sanitized_data['slides'][$slide_index] = $sanitized_slide;
            }
            $sanitized_data['block_id'] = isset($data['media_file']) ? sanitize_text_field($data['media_file']) : '';
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
