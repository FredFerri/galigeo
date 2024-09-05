<?php
require_once plugin_dir_path(__FILE__) . 'class-builder-frontend_slider.php';
require_once plugin_dir_path(__FILE__) . 'class-builder-frontend_video.php';
require_once plugin_dir_path(__FILE__) . 'class-builder-frontend_cta.php';
require_once plugin_dir_path(__FILE__) . 'class-builder-frontend_alternate_visual.php';

class Builder_Frontend {
    private $blocks;

    public function __construct() {
        $this->blocks = [
            'slider' => new Slider_Block_Frontend(),
            'video' => new Video_Block_Frontend(),
            'call_to_action' => new Call_To_Action_Block_Frontend(),
            'alternate_visual' => new Alternate_Visual_Block_Frontend(),
        ];

        add_filter('the_content', array($this, 'display_builder_content'));
    }

    public function display_builder_content($content) {
        if (is_singular('page')) {
            $post_id = get_the_ID();
            $builder_content = $this->get_builder_content($post_id);
            return $builder_content . $content;
        }
        return $content;
    }

    private function get_builder_content($post_id) {
        $blocks = get_post_meta($post_id, '_builder_blocks', true);
        $output = '';
        if (!empty($blocks)) {
            foreach ($blocks as $block) {
                $type = $block['type'];
                $data = $block['data'];

                if (isset($this->blocks[$type])) {
                    $output .= $this->blocks[$type]->render($data);
                }
            }
        }
        return $output;
    }
}

new Builder_Frontend();