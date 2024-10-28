<?php
class Testimonials_Block {
    public function render($data, $index) {
        ?>
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">Configuration des Témoignages</h3>
                <div class="testimonials-slides" data-block-index="<?php echo $index; ?>">
                    <?php
                    $testimonials = $data['testimonials'] ?? [[]];
                    var_dump($testimonials);
                    foreach ($testimonials as $testimony_index => $testimony) {
                        $this->render_testimonial_fields($index, $testimony_index, $testimony);
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
        $testimonial_index = intval($testimonial_index);
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
                <input type="file" name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][testimonials][<?php echo esc_attr($testimonial_index); ?>][image]" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <?php if (!empty($testimonial_data['image'])) : ?>
                    <img src="<?php echo esc_url($testimonial_data['image']); ?>" alt="Image actuelle" class="mt-2 max-w-xs">
                <?php endif; ?>
            </div>
        </div>
        <?php
    }


public function sanitize($data, $post_id, $index) {
    $sanitized_data = [];

    if (isset($data['testimonials']) && is_array($data['testimonials'])) {
        // var_dump($data['testimonials']);
        
        foreach ($data['testimonials'] as $testimonial_index => $testimonial_data) {
            $sanitized_testimonial = array(
                'name'      => isset($testimonial_data['name']) ? sanitize_text_field($testimonial_data['name']) : '',
                'position'  => isset($testimonial_data['position']) ? sanitize_text_field($testimonial_data['position']) : '',
                'quote'     => isset($testimonial_data['quote']) ? wp_kses_post($testimonial_data['quote']) : '',
            );

            // Gestion de l'image
            if (!empty($_FILES['builder_blocks']['name'][$index]['data']['testimonials'][$testimonial_index]['image'])) {
                $file = array(
                    'name'     => $_FILES['builder_blocks']['name'][$index]['data']['testimonials'][$testimonial_index]['image'],
                    'type'     => $_FILES['builder_blocks']['type'][$index]['data']['testimonials'][$testimonial_index]['image'],
                    'tmp_name' => $_FILES['builder_blocks']['tmp_name'][$index]['data']['testimonials'][$testimonial_index]['image'],
                    'error'    => $_FILES['builder_blocks']['error'][$index]['data']['testimonials'][$testimonial_index]['image'],
                    'size'     => $_FILES['builder_blocks']['size'][$index]['data']['testimonials'][$testimonial_index]['image']
                );
                $upload = $this->handle_image_upload($file, $post_id);
                if ($upload && !is_wp_error($upload)) {
                    $sanitized_testimonial['image'] = $upload['url'];
                }
            } elseif (!empty($testimonial_data['image'])) {
                $sanitized_testimonial['image'] = esc_url_raw($testimonial_data['image']);
            }

            // Ajouter le témoignage traité au tableau des témoignages
            $sanitized_data['testimonials'][$testimonial_index] = $sanitized_testimonial;
        }
    }
    // var_dump($sanitized_data);
    // die();
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
