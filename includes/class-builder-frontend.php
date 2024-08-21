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
                }
            }
        }

        return $output;
    }

    private function render_slider($data) {
        $output = '';
        $images = $data['images'] ?? [];
        $button_text = $data['button_text'] ?? '';
        $button_link = $data['button_link'] ?? '';

        if (!empty($images)) {
            $output .= '<div class="builder-slider relative mb-8 h-96">';
            $output .= '<div class="slider-container overflow-hidden h-full">';
            foreach ($images as $key => $image_id) {
                $image_url = wp_get_attachment_image_url($image_id, 'full');
                $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                $output .= '<div class="slider-item absolute inset-0 transition-opacity duration-500 ease-in-out ' . ($key === 0 ? 'opacity-100' : 'opacity-0') . '">';
                $output .= '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '" class="w-full h-full object-cover">';
                $output .= '</div>';
            }
            $output .= '</div>';
            if ($button_text && $button_link) {
                $output .= '<a href="' . esc_url($button_link) . '" class="slider-button absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-300">' . esc_html($button_text) . '</a>';
            }
            $output .= '<button class="prev-slide absolute top-1/2 left-4 transform -translate-y-1/2 bg-white bg-opacity-50 hover:bg-opacity-75 rounded-full p-2 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>';
            $output .= '<button class="next-slide absolute top-1/2 right-4 transform -translate-y-1/2 bg-white bg-opacity-50 hover:bg-opacity-75 rounded-full p-2 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>';
            $output .= '</div>';
        }

        return $output;
    }

    private function render_video($data) {
        $output = '';
        $video_url = $data['url'] ?? '';
        $video_title = $data['title'] ?? '';
        $video_description = $data['description'] ?? '';

        if ($video_url) {
            $output .= '<div class="builder-video mb-8">';
            if ($video_title) {
                $output .= '<h3 class="video-title text-2xl font-bold mb-4">' . esc_html($video_title) . '</h3>';
            }
            $output .= '<div class="video-container relative pb-9/16">' . wp_oembed_get($video_url, array('width' => 800)) . '</div>';
            if ($video_description) {
                $output .= '<div class="video-description mt-4 text-gray-700">' . wp_kses_post($video_description) . '</div>';
            }
            $output .= '</div>';
        }

        return $output;
    }
}

new Builder_Frontend();