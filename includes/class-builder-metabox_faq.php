<?php
class Faq_Block {
    public function render($data, $index) {
        ?>
        <div class="faq-block bg-white shadow-md rounded-lg p-6 mb-6">
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">Configuration des questions</h3>
                <div class="faq-items">
                    <?php
                    $faqs = $data['faqs'] ?? [[]];
                    foreach ($faqs as $faq_index => $faq) {
                        $this->render_faq_fields($index, $faq_index, $faq);
                    }
                    ?>
                </div>
                <button type="button" class="add-faq mt-4 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded" <?php echo (count($faqs) >= 8) ? 'disabled' : ''; ?>>
                    Ajouter une question
                </button>
            </div>
        </div>

        <script type="text/template" id="faq-template">
            <?php $this->render_faq_fields('BLOCK_INDEX', 'FAQ_INDEX', []); ?>
        </script>
        <?php
    }

    private function render_faq_fields($block_index, $faq_index, $faq_data) {
        ?>
        <div class="faq-fields bg-gray-100 p-4 rounded-md mb-4">
            <h4 class="font-semibold mb-2">Question <span class="faq-number"><?php echo esc_html($faq_index + 1); ?></span></h4>
            
            <!-- Question -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Question :</label>
                <textarea name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][faqs][<?php echo esc_attr($faq_index); ?>][question]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"><?php echo esc_textarea($faq_data['question'] ?? ''); ?></textarea>
            </div>

            <!-- Réponse -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Réponse :</label>
                <textarea name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][faqs][<?php echo esc_attr($faq_index); ?>][answer]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"><?php echo esc_textarea($faq_data['answer'] ?? ''); ?></textarea>
            </div>

            <!-- Bouton de suppression -->
            <button type="button" class="remove-faq mt-4 bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                Supprimer cette FAQ
            </button>
        </div>
        <?php
    }

    public function sanitize($data, $post_id, $index) {
        $sanitized_data = [];

        if (isset($data['faqs']) && is_array($data['faqs'])) {
            foreach ($data['faqs'] as $faq_index => $faq_data) {
                $sanitized_data['faqs'][$faq_index] = array(
                    'question' => isset($faq_data['question']) ? wp_kses_post($faq_data['question']) : '',
                    'answer'   => isset($faq_data['answer']) ? wp_kses_post($faq_data['answer']) : '',
                );
            }
        }

        return $sanitized_data;
    }
}
