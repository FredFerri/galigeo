<?php
class Builder_Metabox {
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
        add_action('save_post', array($this, 'save_metabox'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    public function add_meta_box() {
        add_meta_box(
            'mon_builder_personnalise',
            'Mon Builder',
            array($this, 'render_metabox'),
            'page',
            'normal',
            'high'
        );
    }

    public function enqueue_admin_scripts() {
        wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_style('wp-jquery-ui-dialog');
        wp_enqueue_script('builder-admin-js', plugin_dir_url(__FILE__) . '../assets/js/builder-admin.js', array('jquery', 'jquery-ui-dialog'), '1.0', true);
        wp_localize_script('builder-admin-js', 'builderData', array(
            'nonce' => wp_create_nonce('builder_nonce')
        ));
    }

    public function render_metabox($post) {
        wp_nonce_field('save_builder_data', 'builder_nonce');
        $blocks = get_post_meta($post->ID, '_builder_blocks', true);
        ?>
        <div id="builder-container">
            <div id="builder-blocks">
                <?php
                if (!empty($blocks)) {
                    foreach ($blocks as $index => $block) {
                        $this->render_block($block, $index);
                    }
                }
                ?>
            </div>
            <button id="add-block" class="button button-primary">Ajouter un block</button>
            
            <!-- Templates for new blocks -->
            <div id="block-templates" style="display: none;">
                <?php
                $this->render_block(['type' => 'slider', 'data' => []], 'TEMPLATE_INDEX');
                $this->render_block(['type' => 'video', 'data' => []], 'TEMPLATE_INDEX');
                ?>
            </div>
        </div>
        <?php
    }

    public function render_block($block, $index) {
        $type = $block['type'];
        $data = $block['data'];
        ?>
        <div class="builder-block" data-type="<?php echo esc_attr($type); ?>" data-index="<?php echo esc_attr($index); ?>">
            <h3><?php echo ucfirst($type); ?></h3>
            <?php $this->render_block_fields($type, $data, $index); ?>
            <button class="remove-block button button-secondary">Supprimer</button>
        </div>
        <?php
    }

    private function render_block_fields($type, $data, $index) {
        switch ($type) {
            case 'slider':
                ?>
                <p>
                    <label>Images du slider (jusqu'à 3) :</label><br>
                    <input type="file" name="builder_blocks[<?php echo $index; ?>][data][images][]" multiple accept="image/*" max="3">
                </p>
                <p>
                    <label>Texte du bouton :</label><br>
                    <input type="text" name="builder_blocks[<?php echo $index; ?>][data][button_text]" value="<?php echo esc_attr($data['button_text'] ?? ''); ?>">
                </p>
                <p>
                    <label>Lien du bouton :</label><br>
                    <input type="url" name="builder_blocks[<?php echo $index; ?>][data][button_link]" value="<?php echo esc_url($data['button_link'] ?? ''); ?>">
                </p>
                <?php
                break;
            case 'video':
                ?>
                <p>
                    <label>URL de la vidéo :</label><br>
                    <input type="url" name="builder_blocks[<?php echo $index; ?>][data][url]" value="<?php echo esc_url($data['url'] ?? ''); ?>">
                </p>
                <p>
                    <label>Titre de la vidéo :</label><br>
                    <input type="text" name="builder_blocks[<?php echo $index; ?>][data][title]" value="<?php echo esc_attr($data['title'] ?? ''); ?>">
                </p>
                <p>
                    <label>Description de la vidéo :</label><br>
                    <textarea name="builder_blocks[<?php echo $index; ?>][data][description]"><?php echo esc_textarea($data['description'] ?? ''); ?></textarea>
                </p>
                <?php
                break;
        }
        ?>
        <input type="hidden" name="builder_blocks[<?php echo $index; ?>][type]" value="<?php echo esc_attr($type); ?>">
        <?php
    }

    public function save_metabox($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;
        if (!isset($_POST['builder_nonce']) || !wp_verify_nonce($_POST['builder_nonce'], 'save_builder_data')) return;

        $blocks = isset($_POST['builder_blocks']) ? $_POST['builder_blocks'] : array();
        $sanitized_blocks = array();

        foreach ($blocks as $index => $block) {
            if (!isset($block['type'])) {
                continue; // Ignorer les blocs sans type
            }

            $type = sanitize_text_field($block['type']);
            $data = isset($block['data']) ? $block['data'] : array();

            switch ($type) {
                case 'slider':
                    $sanitized_data = array(
                        'button_text' => isset($data['button_text']) ? sanitize_text_field($data['button_text']) : '',
                        'button_link' => isset($data['button_link']) ? esc_url_raw($data['button_link']) : ''
                    );
                    // Gérer les images ici si nécessaire
                    break;
                case 'video':
                    $sanitized_data = array(
                        'url' => isset($data['url']) ? esc_url_raw($data['url']) : '',
                        'title' => isset($data['title']) ? sanitize_text_field($data['title']) : '',
                        'description' => isset($data['description']) ? wp_kses_post($data['description']) : ''
                    );
                    break;
                default:
                    continue 2; // Ignorer les types de blocs inconnus
            }

            $sanitized_blocks[] = array(
                'type' => $type,
                'data' => $sanitized_data
            );
        }

        update_post_meta($post_id, '_builder_blocks', $sanitized_blocks);
    }
}

new Builder_Metabox();