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
                $this->render_block(['type' => 'call_to_action', 'data' => []], 'TEMPLATE_INDEX');
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
                case 'call_to_action':
                    ?>
                    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                        <!-- Titre -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Titre :</label>
                            <input type="text" name="builder_blocks[<?php echo $index; ?>][data][title]" value="<?php echo esc_attr($data['title'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <!-- Type de balise titre -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Type de balise titre :</label>
                            <select name="builder_blocks[<?php echo $index; ?>][data][title_tag]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <?php
                                $title_tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
                                foreach ($title_tags as $tag) {
                                    echo '<option value="' . $tag . '"' . selected($data['title_tag'] ?? 'h2', $tag, false) . '>' . $tag . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description :</label>
                            <input type="text" name="builder_blocks[<?php echo $index; ?>][data][description]" value="<?php echo esc_attr($data['description'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <!-- Taille de police de la description -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Taille de police de la description :</label>
                            <input type="number" name="builder_blocks[<?php echo $index; ?>][data][description_font_size]" value="<?php echo esc_attr($data['description_font_size'] ?? '16'); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <!-- Couleur du texte de description -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Couleur du texte de description :</label>
                            <input type="color" name="builder_blocks[<?php echo $index; ?>][data][description_color]" value="<?php echo esc_attr($data['description_color'] ?? '#000000'); ?>" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-10 w-20">
                        </div>

                        <!-- Type de background -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Type de background :</label>
                            <select name="builder_blocks[<?php echo $index; ?>][data][bg_type]" class="bg-type-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="color" <?php selected($data['bg_type'] ?? 'color', 'color'); ?>>Couleur</option>
                                <option value="image" <?php selected($data['bg_type'] ?? 'color', 'image'); ?>>Image</option>
                            </select>
                        </div>

                        <!-- Couleur de background -->
                        <div class="mb-4 bg-color-field <?php echo ($data['bg_type'] ?? 'color') === 'image' ? 'hidden' : ''; ?>">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Couleur de background :</label>
                            <input type="color" name="builder_blocks[<?php echo $index; ?>][data][bg_color]" value="<?php echo esc_attr($data['bg_color'] ?? '#ffffff'); ?>" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-10 w-20">
                        </div>

                        <!-- Image de background -->
                        <div class="mb-4 bg-image-field <?php echo ($data['bg_type'] ?? 'color') === 'color' ? 'hidden' : ''; ?>">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Image de background :</label>
                            <input type="file" name="builder_blocks[<?php echo $index; ?>][data][bg_image]" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <?php if (!empty($data['bg_image'])) : ?>
                                <img src="<?php echo esc_url($data['bg_image']); ?>" alt="Background actuel" class="mt-2 max-w-xs">
                            <?php endif; ?>
                        </div>

                        <!-- Boutons -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Boutons :</label>
                            <div id="cta-buttons-container-<?php echo $index; ?>">
                            <?php
                                $buttons = $data['buttons'] ?? [[]];
                                foreach ($buttons as $button_index => $button) {
                                    $this->render_cta_button_fields($index, intval($button_index), $button);
                                }
                            ?>
                            </div>
                            <?php if (count($buttons) < 2) : ?>
                                <button type="button" class="add-cta-button mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" data-block-index="<?php echo $index; ?>">
                                    Ajouter un bouton
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <script type="text/template" id="cta-button-template-<?php echo esc_attr($index); ?>">
                        <?php $this->render_cta_button_fields($index, 'BUTTON_INDEX', []); ?>
                    </script>
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

    private function render_cta_button_fields($block_index, $button_index, $button_data) {
        // Assurez-vous que $button_index est un entier
        $button_index = intval($button_index);
        ?>
        <div class="cta-button-fields bg-gray-100 p-4 rounded-md mb-4">
            <h4 class="font-semibold mb-2">Bouton <?php echo esc_html($button_index + 1); ?></h4>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Texte du bouton :</label>
                <input type="text" name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][buttons][<?php echo esc_attr($button_index); ?>][text]" value="<?php echo esc_attr($button_data['text'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Couleur du bouton :</label>
                <input type="color" name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][buttons][<?php echo esc_attr($button_index); ?>][color]" value="<?php echo esc_attr($button_data['color'] ?? '#000000'); ?>" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-10 w-20">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">URL du bouton :</label>
                <input type="url" name="builder_blocks[<?php echo esc_attr($block_index); ?>][data][buttons][<?php echo esc_attr($button_index); ?>][url]" value="<?php echo esc_url($button_data['url'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <?php if ($button_index > 0) : ?>
                <button type="button" class="remove-cta-button mt-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Supprimer ce bouton
                </button>
            <?php endif; ?>


        </div>
        <?php
    }

    public function save_metabox($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;
        if (!isset($_POST['builder_nonce']) || !wp_verify_nonce($_POST['builder_nonce'], 'save_builder_data')) return;

        $blocks = isset($_POST['builder_blocks']) ? $_POST['builder_blocks'] : array();
        // Filtrer les blocs pour exclure ceux avec la clé 'TEMPLATE_INDEX'
        $filtered_blocks = array_filter($blocks, function($key) {
            // Exclure les blocs avec la clé 'TEMPLATE_INDEX'
            return $key !== 'TEMPLATE_INDEX';
        }, ARRAY_FILTER_USE_KEY);             
        $sanitized_blocks = array();
        foreach ($filtered_blocks as $index => $block) {
            if (!isset($block['type'])) {
                continue; // Ignorer les blocs sans type
            }

            $type = sanitize_text_field($block['type']);
            $data = isset($block['data']) ? $block['data'] : array();

            switch ($type) {
                case 'slider':
                $sanitized_data = array();

                if (isset($data['slides']) && is_array($data['slides'])) {
                    foreach ($data['slides'] as $slide_index => $slide_data) {
                        $sanitized_slide = array(
                            'title'       => isset($slide_data['title']) ? sanitize_text_field($slide_data['title']) : '',
                            'title_tag'   => isset($slide_data['title_tag']) ? sanitize_text_field($slide_data['title_tag']) : 'h2',
                            'bg_type'     => isset($slide_data['bg_type']) ? sanitize_text_field($slide_data['bg_type']) : 'color',
                            'bg_color'    => isset($slide_data['bg_color']) ? sanitize_hex_color($slide_data['bg_color']) : '#ffffff',
                            'show_button' => isset($slide_data['show_button']) ? (bool)$slide_data['show_button'] : false,
                            'button_text' => isset($slide_data['button_text']) ? sanitize_text_field($slide_data['button_text']) : '',
                            'button_url'  => isset($slide_data['button_url']) ? esc_url_raw($slide_data['button_url']) : '',
                            'button_color'=> isset($slide_data['button_color']) ? sanitize_hex_color($slide_data['button_color']) : '#000000',
                        );

                        // Gestion de l'image de background
                        if ($slide_data['bg_type'] === 'image') {
                            if (!empty($_FILES['builder_blocks']['name'][$index]['data']['slides'][$slide_index]['bg_image'])) {
                                $file = array(
                                    'name'     => $_FILES['builder_blocks']['name'][$index]['data']['slides'][$slide_index]['bg_image'],
                                    'type'     => $_FILES['builder_blocks']['type'][$index]['data']['slides'][$slide_index]['bg_image'],
                                    'tmp_name' => $_FILES['builder_blocks']['tmp_name'][$index]['data']['slides'][$slide_index]['bg_image'],
                                    'error'    => $_FILES['builder_blocks']['error'][$index]['data']['slides'][$slide_index]['bg_image'],
                                    'size'     => $_FILES['builder_blocks']['size'][$index]['data']['slides'][$slide_index]['bg_image']
                                );
                                $upload = $this->handle_image_upload($file, $post_id);
                                if ($upload && !is_wp_error($upload)) {
                                    $sanitized_slide['bg_image'] = $upload['url'];
                                }
                            } elseif (!empty($slide_data['bg_image'])) {
                                // Conserver l'image existante si aucune nouvelle image n'a été uploadée
                                $sanitized_slide['bg_image'] = esc_url_raw($slide_data['bg_image']);
                            }
                        }

                        $sanitized_data['slides'][$slide_index] = $sanitized_slide;
                    }
                }
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
                    case 'call_to_action':
                        $sanitized_data = array(
                            'title' => isset($data['title']) ? sanitize_text_field($data['title']) : '',
                            'title_tag' => isset($data['title_tag']) ? sanitize_text_field($data['title_tag']) : 'h2',
                            'description' => isset($data['description']) ? wp_kses_post($data['description']) : '',
                            'description_font_size' => isset($data['description_font_size']) ? intval($data['description_font_size']) : 16,
                            'description_color' => isset($data['description_color']) ? sanitize_hex_color($data['description_color']) : '#000000',
                            'bg_type' => isset($data['bg_type']) ? sanitize_text_field($data['bg_type']) : 'color',
                            'bg_color' => isset($data['bg_color']) ? sanitize_hex_color($data['bg_color']) : '#ffffff',
                        );

                        // Gestion de l'image de background
                        if ($data['bg_type'] === 'image') {
                            if (!empty($_FILES['builder_blocks']['name'][$index]['data']['bg_image'])) {
                                $file = array(
                                    'name'     => $_FILES['builder_blocks']['name'][$index]['data']['bg_image'],
                                    'type'     => $_FILES['builder_blocks']['type'][$index]['data']['bg_image'],
                                    'tmp_name' => $_FILES['builder_blocks']['tmp_name'][$index]['data']['bg_image'],
                                    'error'    => $_FILES['builder_blocks']['error'][$index]['data']['bg_image'],
                                    'size'     => $_FILES['builder_blocks']['size'][$index]['data']['bg_image']
                                );
                                $upload = $this->handle_image_upload($file, $post_id);
                                if ($upload && !is_wp_error($upload)) {
                                    $sanitized_data['bg_image'] = $upload['url'];
                                }
                            } elseif (!empty($data['bg_image'])) {
                                $sanitized_data['bg_image'] = esc_url_raw($data['bg_image']);
                            }
                        }

                        // Gestion des boutons
                        $sanitized_data['buttons'] = array();
                        if (isset($data['buttons']) && is_array($data['buttons'])) {
                            foreach ($data['buttons'] as $button_index => $button) {
                                $sanitized_data['buttons'][$button_index] = array(
                                    'text' => isset($button['text']) ? sanitize_text_field($button['text']) : '',
                                    'color' => isset($button['color']) ? sanitize_hex_color($button['color']) : '#000000',
                                    'url' => isset($button['url']) ? esc_url_raw($button['url']) : '',
                                );
                            }
                        }
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

new Builder_Metabox();