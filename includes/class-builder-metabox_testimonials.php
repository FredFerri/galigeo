<?php
class Testimonials_Block {
    public function render($data, $index) {
        ?>
        <div class="testimonials-block bg-white shadow-md rounded-lg p-6 mb-6">
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">Configuration des Témoignages</h3>
                <div class="testimonials-slides">
                    <?php
                    $testimonials = $data['testimonials'] ?? [[]];
                    foreach ($testimonials as $testimonial_index => $testimonial) {
                        $this->render_testimonial_fields($index, $testimonial_index, $testimonial);
                    }
                    ?>
                </div>
                <button type="button" class="add-testimonial mt-4 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded" <?php echo (count($testimonials) >= 6) ? 'disabled' : ''; ?>>
                    Ajouter un témoignage
                </button>
            </div>
        </div>

        <script type="text/template" id="testimonial-template">
            <?php $this->render_testimonial_fields('BLOCK_INDEX', 'TESTIMONIAL_INDEX', []); ?>
        </script>
        <?php
    }

    private function render_testimonial_fields($block_index, $testimonial_index, $testimonial_data) {
        ?>
        <div class="testimonial-fields bg-gray-100 p-4 rounded-md mb-4">
            <h4 class="font-semibold mb-2">Témoignage <span class="testimonial-number"><?php echo esc_html($testimonial_index + 1); ?></span></h4>
            
            <!-- Nom de la personne -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nom de la personne :</label>
                <input type="text" name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][testimonials][<?php echo esc_attr($testimonial_index); ?>][name]" value="<?php echo esc_attr($testimonial_data['name'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <!-- Poste de la personne -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Poste de la personne :</label>
                <input type="text" name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][testimonials][<?php echo esc_attr($testimonial_index); ?>][position]" value="<?php echo esc_attr($testimonial_data['position'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <!-- Citation -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Citation :</label>
                <textarea name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][testimonials][<?php echo esc_attr($testimonial_index); ?>][quote]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"><?php echo esc_textarea($testimonial_data['quote'] ?? ''); ?></textarea>
            </div>

            <!-- Image -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Image :</label>
                <button type="button" class="extra-image-selector bg-blue-500 text-white py-2 px-4 rounded mb-2">Ajouter ou sélectionner une image</button>
                <input type="hidden" name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][testimonials][<?php echo esc_attr($testimonial_index); ?>][image]" value="<?php echo esc_url($testimonial_data['image'] ?? ''); ?>" class="extra-image-url">
                <div class="extra-image-container <?php echo empty($testimonial_data['image']) ? 'hidden' : ''; ?>">
                    <img src="<?php echo esc_url($testimonial_data['image']); ?>" alt="Image actuelle" class="extra-image-preview max-w-xs">
                    <button type="button" class="remove-extra-image bg-red-500 text-white p-1 rounded-full">&times;</button>
                </div>
            </div>

            <!-- Bouton de suppression -->
            <button type="button" class="remove-testimonial mt-4 bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                Supprimer ce témoignage
            </button>
        </div>
        <?php
    }

    public function sanitize($data, $post_id, $index) {
        $sanitized_data = [];

        if (isset($data['testimonials']) && is_array($data['testimonials'])) {
            foreach ($data['testimonials'] as $testimonial_index => $testimonial_data) {
                $sanitized_testimonial = array(
                    'name'      => isset($testimonial_data['name']) ? sanitize_text_field($testimonial_data['name']) : '',
                    'position'  => isset($testimonial_data['position']) ? sanitize_text_field($testimonial_data['position']) : '',
                    'quote'     => isset($testimonial_data['quote']) ? wp_kses_post($testimonial_data['quote']) : '',
                );

                if (!empty($testimonial_data['image'])) {
                    $sanitized_testimonial['image'] = esc_url_raw($testimonial_data['image']);
                }

                $sanitized_data['testimonials'][$testimonial_index] = $sanitized_testimonial;
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
