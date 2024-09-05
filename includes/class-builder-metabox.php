<?php
require_once plugin_dir_path(__FILE__) . 'class-builder-metabox_slider.php';
require_once plugin_dir_path(__FILE__) . 'class-builder-metabox_video.php';
require_once plugin_dir_path(__FILE__) . 'class-builder-metabox_cta.php';
require_once plugin_dir_path(__FILE__) . 'class-builder-metabox_visual_alt.php';

class Builder_Metabox {
    private $blocks;

    public function __construct() {
        $this->blocks = [
            'slider' => new Slider_Block(),
            'video' => new Video_Block(),
            'call_to_action' => new Call_To_Action_Block(),
            'alternate_visual' => new Alternate_Visual_Block(),
        ];

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
        wp_enqueue_script('builder-admin-js', plugin_dir_url(__FILE__) . 'assets/js/builder-admin.js', array('jquery', 'jquery-ui-dialog'), '1.0', true);
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
            
            <div id="block-templates" style="display: none;">
                <?php
                foreach ($this->blocks as $type => $block_instance) {
                    $this->render_block(['type' => $type, 'data' => []], 'TEMPLATE_INDEX');
                }
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
            <input type="hidden" name="builder_blocks[<?php echo $index; ?>][type]" value="<?php echo esc_attr($type); ?>">
            <h3 class="text-2xl antialiased font-bold md:text-3xl dark:text-gray-50 galigeo"><?php echo ucfirst($type); ?></h3>
            <?php
            if (isset($this->blocks[$type])) {
                $this->blocks[$type]->render($data, $index);
            }
            ?>
            <button class="remove-block button button-secondary">Supprimer</button>
        </div>
        <?php
    }

public function save_metabox($post_id) {
    // Vérifications de sécurité
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (!isset($_POST['builder_nonce']) || !wp_verify_nonce($_POST['builder_nonce'], 'save_builder_data')) return;

    // Récupération des blocs soumis
    $blocks = isset($_POST['builder_blocks']) ? $_POST['builder_blocks'] : array();
    // var_dump($blocks);
    $sanitized_blocks = array();

    // Boucle sur les blocs soumis
    foreach ($blocks as $index => $block) {
        if (!isset($block['type']) || !isset($this->blocks[$block['type']]) || $index == 'TEMPLATE_INDEX') {
            continue; // Si le type n'existe pas ou est invalide, on ignore ce bloc
        }

        $type = sanitize_text_field($block['type']);
        $data = isset($block['data']) ? $block['data'] : array();

        // On appelle la méthode sanitize pour chaque bloc et on stocke les blocs avec leur type
        $sanitized_blocks[] = [
            'type' => $type,
            'data' => $this->blocks[$type]->sanitize($data, $post_id, $index)
        ];
    }
    // var_dump($sanitized_blocks);
    // die();

    // Mise à jour des métadonnées du post avec les blocs
    update_post_meta($post_id, '_builder_blocks', $sanitized_blocks);
}


    public function handle_image_upload($file, $post_id) {
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

new Builder_Metabox();