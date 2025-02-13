<?php
class Video_Block {
    public function render($data, $index) {
        ?>
        <div class="video-block bg-white shadow-md rounded-lg p-6 mb-6">
            <p class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">URL de la vidéo :</label>
                <input type="url" name="builder_blocks[<?php echo $index; ?>][data][url]" value="<?php echo esc_url($data['url'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </p>
            <p class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de balise titre :</label>
                <select name="builder_blocks[<?php echo $index; ?>][data][title_tag]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <?php
                    $title_tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
                    foreach ($title_tags as $tag) {
                        echo '<option value="' . $tag . '"' . selected($data['title_tag'] ?? 'h3', $tag, false) . '>' . $tag . '</option>';
                    }
                    ?>
                </select>
            </p>

            <!-- ID du block -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">ID du block :</label>
                <input type="text" name="builder_blocks[<?php echo $index; ?>][data][block_id]" value="<?php echo esc_attr($data['block_id'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <p class="text-sm text-gray-500 mt-1">Entrez un identifiant unique pour ce block (lettres, chiffres, tirets uniquement).</p>
            </div>

            <p class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Titre de la vidéo :</label>
                <input type="text" name="builder_blocks[<?php echo $index; ?>][data][title]" value="<?php echo esc_attr($data['title'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </p>
            <p class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description de la vidéo :</label>
                <textarea name="builder_blocks[<?php echo $index; ?>][data][description]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" rows="4"><?php echo esc_textarea($data['description'] ?? ''); ?></textarea>
            </p>
            <p class="mb-4">
                <label class="inline-flex items-center">
                <input type="checkbox" class="show_button_checkbox" data-index="<?php echo $index; ?>" name="builder_blocks[<?php echo $index; ?>][data][show_button]" value="1" <?php checked(isset($data['show_button']) && $data['show_button'] == 1); ?> class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">


                    <span class="ml-2 text-sm text-gray-700">Ajouter un bouton</span>
                </label>
            </p>
            <div class="button-options <?php echo (isset($data['show_button']) && $data['show_button'] == 1) ? 'block' : 'hidden'; ?> bg-gray-50 p-4 rounded-md" data-index="<?php echo $index; ?>">


                <p class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Texte du bouton :</label>
                    <input type="text" name="builder_blocks[<?php echo $index; ?>][data][button_text]" value="<?php echo esc_attr($data['button_text'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </p>
                <p class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lien du bouton :</label>
                    <input type="url" name="builder_blocks[<?php echo $index; ?>][data][button_link]" value="<?php echo esc_url($data['button_link'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </p>
                <p class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Couleur du bouton :</label>
                    <input type="color" name="builder_blocks[<?php echo $index; ?>][data][button_color]" value="<?php echo esc_attr($data['button_color'] ?? '#000000'); ?>" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-10 w-20">
                </p>
            </div>
        </div>
        <?php
    }

    public function sanitize($data, $post_id, $index) {
        return array(
            'block_id' => isset($data['block_id']) ? sanitize_title($data['block_id']) : '',  
            'url' => isset($data['url']) ? esc_url_raw($data['url']) : '',
            'title_tag' => isset($data['title_tag']) ? sanitize_text_field($data['title_tag']) : 'h3',
            'title' => isset($data['title']) ? sanitize_text_field($data['title']) : '',
            'description' => isset($data['description']) ? wp_kses_post($data['description']) : '',
            'show_button' => isset($data['show_button']) ? 1 : 0,
            'button_text' => isset($data['button_text']) ? sanitize_text_field($data['button_text']) : '',
            'button_link' => isset($data['button_link']) ? esc_url_raw($data['button_link']) : '',
            'button_color' => isset($data['button_color']) ? sanitize_hex_color($data['button_color']) : '#000000'
        );
    }
}