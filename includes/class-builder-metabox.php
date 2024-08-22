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
            <h3 class="text-2xl antialiased font-bold md:text-3xl dark:text-gray-50 galigeo"><?php echo ucfirst($type); ?></h3>
            <?php $this->render_block_fields($type, $data, $index); ?>
            <button class="remove-block button button-secondary">Supprimer</button>
        </div>
        <?php
    }

    private function render_block_fields($type, $data, $index) {
        switch ($type) {
            case 'slider':
                ?>
                <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Configuration du Slider</h3>
                        <div id="slider-slides" data-block-index="<?php echo $index; ?>">
                            <?php
                            $slides = $data['slides'] ?? [[]];
                            var_dump($slides);
                            foreach ($slides as $slide_index => $slide) {
                                $this->render_slide_fields($index, $slide_index, $slide);
                            }
                            ?>
                        </div>
                        <button type="button" class="add-slide mt-4 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded" <?php echo (count($slides) >= 4) ? 'disabled' : ''; ?>>
                            Ajouter un slide
                        </button>
                    </div>
                </div>

                <script type="text/template" id="slide-template">
                    <?php $this->render_slide_fields('BLOCK_INDEX', 'SLIDE_INDEX', []); ?>
                </script>
                <?php
            break;
            case 'video':
                ?>
                <div class="bg-white shadow-md rounded-lg p-6 mb-6">
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
                            <input type="checkbox" name="builder_blocks[<?php echo $index; ?>][data][show_button]" value="1" <?php checked(isset($data['show_button']) && $data['show_button'] == 1); ?> class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Ajouter un bouton</span>
                        </label>
                    </p>
                    <div class="button-options <?php echo (isset($data['show_button']) && $data['show_button'] == 1) ? 'block' : 'hidden'; ?> bg-gray-50 p-4 rounded-md">
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
                break;
        }
        ?>
        <input type="hidden" name="builder_blocks[<?php echo $index; ?>][type]" value="<?php echo esc_attr($type); ?>">
        <?php
    }

private function render_slide_fields($block_index, $slide_index, $slide_data) {
    // Assurez-vous que $slide_index est un entier
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
        <div class="mb-4 bg-color-field <?php echo ($slide_data['bg_type'] ?? 'color') === 'image' ? 'hidden' : ''; ?>">
            <label class="block text-sm font-medium text-gray-700 mb-2">Couleur de background :</label>
            <input type="color" name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][slides][<?php echo esc_attr($slide_index); ?>][bg_color]" value="<?php echo esc_attr($slide_data['bg_color'] ?? '#ffffff'); ?>" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-10 w-20">
        </div>

        <!-- Image de background -->
        <div class="mb-4 bg-image-field <?php echo ($slide_data['bg_type'] ?? 'color') === 'color' ? 'hidden' : ''; ?>">
            <label class="block text-sm font-medium text-gray-700 mb-2">Image de background :</label>
            <input type="file" name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][slides][<?php echo esc_attr($slide_index); ?>][bg_image]" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            <?php if (!empty($slide_data['bg_image'])) : ?>
                <img src="<?php echo esc_url($slide_data['bg_image']); ?>" alt="Background actuel" class="mt-2 max-w-xs">
            <?php endif; ?>
        </div>

        <!-- Checkbox pour le bouton -->
        <div class="mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][slides][<?php echo esc_attr($slide_index); ?>][show_button]" value="1" <?php checked(!empty($slide_data['show_button']), true); ?> class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <span class="ml-2 text-sm text-gray-700">Afficher un bouton</span>
            </label>
        </div>

        <!-- Options du bouton -->
        <div class="button-options <?php echo !empty($slide_data['show_button']) ? 'block' : 'hidden'; ?>">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Texte du bouton :</label>
                <input type="text" name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][slides][<?php echo esc_attr($slide_index); ?>][button_text]" value="<?php echo esc_attr($slide_data['button_text'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">URL du bouton :</label>
                <input type="url" name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][slides][<?php echo esc_attr($slide_index); ?>][button_url]" value="<?php echo esc_url($slide_data['button_url'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Couleur du bouton :</label>
                <input type="color" name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][slides][<?php echo esc_attr($slide_index); ?>][button_color]" value="<?php echo esc_attr($slide_data['button_color'] ?? '#000000'); ?>" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-10 w-20">
            </div>
        </div>

        <button type="button" class="remove-slide mt-4 bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
            Supprimer ce slide
        </button>
    </div>
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
                        'title_tag' => isset($data['title_tag']) ? sanitize_text_field($data['title_tag']) : 'h3',
                        'title' => isset($data['title']) ? sanitize_text_field($data['title']) : '',
                        'description' => isset($data['description']) ? wp_kses_post($data['description']) : '',
                        'show_button' => isset($data['show_button']) ? 1 : 0,
                        'button_text' => isset($data['button_text']) ? sanitize_text_field($data['button_text']) : '',
                        'button_link' => isset($data['button_link']) ? esc_url_raw($data['button_link']) : '',
                        'button_color' => isset($data['button_color']) ? sanitize_hex_color($data['button_color']) : '#000000'
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