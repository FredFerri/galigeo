<?php
class Video_Block_Frontend {
    public function render($data) {
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
            $output .= '<div class="galigeo-builder-video mb-8 compressed-width flex flex-col">';
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
}