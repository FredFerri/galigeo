<?php
class Builder_Frontend {
    public function __construct() {
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

                switch ($type) {
                    case 'slider':
                        $output .= $this->render_slider($data);
                        break;
                    case 'video':
                        $output .= $this->render_video($data);
                        break;
                    case 'call_to_action':
                        $output .= $this->render_call_to_action($data);
                        break;                        
                }
            }
        }
        return $output;
    }

private function render_slider($data) {
    $slides = $data['slides'] ?? [];
    if (count($slides) < 2) return '';

    $output = '<div class="galigeo-slider-wrapper">';
    $output .= '<div class="galigeo-slider" style="--slide-count: ' . count($slides) . ';">';
    $output .= '<div class="galigeo-slider-container">';

    foreach ($slides as $index => $slide) {
        $bg_type = $slide['bg_type'] ?? 'color';
        $bg_value = $bg_type === 'color' ? ($slide['bg_color'] ?? '#ffffff') : ($slide['bg_image'] ?? '');
        $title = $slide['title'] ?? '';
        $title_tag = $slide['title_tag'] ?? 'h2';
        $button_text = $slide['button_text'] ?? '';
        $button_url = $slide['button_url'] ?? '';
        $button_color = $slide['button_color'] ?? '#000000';

        $output .= '<div class="galigeo-slide" data-index="' . $index . '" style="' . ($bg_type === 'color' ? "background-color: $bg_value;" : "background-image: url('$bg_value');") . '">';
        $output .= '<div class="galigeo-slide-content">';
        if ($title) {
            $output .= "<$title_tag class='galigeo-slide-title'>" . esc_html($title) . "</$title_tag>";
        }
        if ($button_text && $button_url) {
            $output .= '<a href="' . esc_url($button_url) . '" class="galigeo-slide-button" style="background-color: ' . esc_attr($button_color) . ';">' . esc_html($button_text) . '</a>';
        }
        $output .= '</div></div>';
    }

    $output .= '</div>';
    $output .= '<button class="galigeo-slider-nav galigeo-slider-prev">&larr;</button>';
    $output .= '<button class="galigeo-slider-nav galigeo-slider-next">&rarr;</button>';
    $output .= '</div></div>';

    return $output;
}

    private function render_video($data) {
        $output = '';
        $video_url = $data['url'] ?? '';
        $video_title = $data['title'] ?? '';
        $video_description = $data['description'] ?? '';
        $title_tag = $data['title_tag'] ?? 'h3';
        $show_button = $data['show_button'] ?? 0;
        $button_text = $data['button_text'] ?? '';
        $button_link = $data['button_link'] ?? '';
        $button_color = $data['button_color'] ?? '#000000';

        if ($video_url) {
            $output .= '<div class="builder-video mb-8 compressed-width flex flex-col">';
            if ($video_title) {
                $output .= "<{$title_tag} class=\"video-title text-2xl font-bold mb-4 galigeo-bleu text-center\">" . esc_html($video_title) . "</{$title_tag}>";
            }
            $output .= '<div class="video-container relative pb-9/16 w-3/4 m-auto">' . wp_oembed_get($video_url, array('width' => 800)) . '</div>';
            if ($video_description) {
                $output .= '<div class="video-description mt-4 text-gray-700 w-3/4 m-auto text-center">' . wp_kses_post($video_description) . '</div>';
            }
            if ($show_button && $button_text && $button_link) {
                $output .= '<a href="' . esc_url($button_link) . '" class="video-button mt-4 inline-block px-4 py-2 rounded m-auto text-center" style="background-color: ' . esc_attr($button_color) . '; color: #ffffff;">' . esc_html($button_text) . '</a>';
            }
            $output .= '</div>';
        }

        return $output;
    }

    private function render_call_to_action($data) {
        $title = $data['title'] ?? '';
        $title_tag = $data['title_tag'] ?? 'h2';
        $description = $data['description'] ?? '';
        $description_font_size = $data['description_font_size'] ?? '16';
        $description_color = $data['description_color'] ?? '#000000';
        $bg_type = $data['bg_type'] ?? 'color';
        $bg_value = $bg_type === 'color' ? ($data['bg_color'] ?? '#ffffff') : ($data['bg_image'] ?? '');
        $buttons = $data['buttons'] ?? [];

        $bg_style = $bg_type === 'color' 
            ? "background-color: $bg_value;" 
            : "background-image: url('$bg_value'); background-size: cover; background-position: center;";

        $output = '<div class="galigeo-cta py-16 my-12" style="' . $bg_style . '">'; // Ajout de my-12 pour les marges verticales
        $output .= '<div class="container mx-auto px-4 text-center">';
        
        if ($title) {
            $output .= "<$title_tag class='text-3xl font-bold mb-8'>" . esc_html($title) . "</$title_tag>";
        }
        
        if ($description) {
            $output .= '<p class="mb-8" style="font-size: ' . esc_attr($description_font_size) . 'px; color: ' . esc_attr($description_color) . ';">' . wp_kses_post($description) . '</p>';
        }
        
        if (!empty($buttons)) {
            $output .= '<div class="flex justify-center space-x-4 cta-btn">';
            foreach ($buttons as $button) {
                $button_text = $button['text'] ?? '';
                $button_url = $button['url'] ?? '';
                $button_color = $button['color'] ?? '#000000';
                    $output .= '<a href="' . esc_url($button_url) . '" class="px-6 py-3 rounded-lg font-semibold text-white" style="background-color: ' . esc_attr($button_color) . ';">' . esc_html($button_text) . '</a>';
            }
            $output .= '</div>';
        }
        
        $output .= '</div></div>';

        return $output;
    }

}

new Builder_Frontend();