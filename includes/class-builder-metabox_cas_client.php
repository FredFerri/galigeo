<?php
class Cas_Client_Block {
    public function render($data, $index) {
        ?>
        <div class="cas-client-block bg-white shadow-md rounded-lg p-6 mb-6 builder-block">

            <!-- ID du block -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">ID du block :</label>
                <input type="text" name="builder_blocks[<?php echo $index; ?>][data][block_id]" value="<?php echo esc_attr($data['block_id'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <p class="text-sm text-gray-500 mt-1">Entrez un identifiant unique pour ce block (lettres, chiffres, tirets uniquement).</p>
            </div>

            <!-- Titre -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Titre :</label>
                <input type="text" name="builder_blocks[<?php echo esc_attr($index); ?>][data][title]" value="<?php echo esc_attr($data['title'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <!-- Type de balise titre -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de balise titre :</label>
                <select name="builder_blocks[<?php echo esc_attr($index); ?>][data][title_tag]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <?php
                    $title_tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
                    foreach ($title_tags as $tag) {
                        echo '<option value="' . esc_attr($tag) . '"' . selected($data['title_tag'] ?? 'h2', $tag, false) . '>' . esc_html($tag) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <!-- Sélection des cas clients -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Sélectionnez jusqu'à 6 cas clients :</label>
                <div id="cas-clients-container-<?php echo esc_attr($index); ?>">
                    <?php
                    $selected_clients = $data['clients'] ?? [];
                    for ($i = 0; $i < 6; $i++) {
                        $selected_client_id = $selected_clients[$i] ?? '';
                        ?>
                        <div class="cas-client-field mb-4">
                            <select name="builder_blocks[<?php echo esc_attr($index); ?>][data][clients][<?php echo esc_attr($i); ?>]" class="cas-client-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">-- Sélectionnez un cas client --</option>
                                <?php
                                $cas_clients = get_posts(['post_type' => 'cas_client', 'numberposts' => -1]);
                                foreach ($cas_clients as $cas_client) {
                                    echo '<option value="' . esc_attr($cas_client->ID) . '"' . selected($selected_client_id, $cas_client->ID, false) . '>' . esc_html($cas_client->post_title) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }

    public function sanitize($data, $post_id, $index) {
        $sanitized_data = [
            'block_id' => isset($data['block_id']) ? sanitize_text_field($data['block_id']) : '',
            'title' => isset($data['title']) ? sanitize_text_field($data['title']) : '',
            'title_tag' => isset($data['title_tag']) ? sanitize_text_field($data['title_tag']) : 'h2',
        ];

        // Sanitize cas clients
        $sanitized_data['clients'] = [];
        if (isset($data['clients']) && is_array($data['clients'])) {
            foreach ($data['clients'] as $client_id) {
                if (is_numeric($client_id) && get_post($client_id)) {
                    $sanitized_data['clients'][] = intval($client_id);
                }
            }
        }

        return $sanitized_data;
    }
}
?>
