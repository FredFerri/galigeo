<?php
class Slider_Block_Frontend {
    public function render($data) {
        // var_dump($data);
        $slides = $data['slides'] ?? [];
        $block_id = $data['block_id'] ?? '';
        // if (count($slides) < 2) return '';

        $output = '<div id="' . esc_attr($block_id) . '" class="galigeo-slider-wrapper">';
        $output .= '<div class="galigeo-slider" style="--slide-count: ' . count($slides) . ';">';
        $output .= '<div class="galigeo-slider-container">';

        foreach ($slides as $index => $slide) {
            // var_dump($slide);
            $bg_type = $slide['bg_type'] ?? 'color';
            $bg_value = $bg_type === 'color' ? ($slide['bg_color'] ?? '#ffffff') : ($slide['bg_image'] ?? '');
            $title = $slide['title'] ?? '';
            $title_tag = $slide['title_tag'] ?? 'h2';
            $media_type = $slide['media_type'] ?? 'image';
            $media_file = $slide['media_file'] ?? '';
            $show_button = $slide['show_button'] ?? '';
            $button_text = $slide['button_text'] ?? '';
            $button_url = $slide['button_url'] ?? '';
            $button_color = $slide['button_color'] ?? '#000000';

            $output .= '<div class="galigeo-slide" data-index="' . $index . '" style="' . ($bg_type === 'color' ? "background-color: $bg_value;" : "background-image: url('$bg_value');") . '">';
            $output .= '<div class="galigeo-slide-content">';

            $output .="<div class='galigeo-left-section'>";
            // Affichage du titre
            if ($title) {
                $output .= "<$title_tag class='galigeo-slide-title galigeo-title'>" . esc_html($title) . "</$title_tag>";
            }
            if ($show_button) {
                $output .= '<a href="' . esc_url($button_url) . '" class="galigeo-slide-button mt-4 inline-block px-4 py-2 rounded m-auto text-center" style="background-color: ' . esc_attr($button_color) . '; color: #ffffff;">' . esc_html($button_text) . '</a>';
            }        
            $output .="</div>";

            // Affichage du média (image ou vidéo)
            if ($media_file) {
                if ($media_type === 'video') {
                    $output .= '<div class="galigeo-slide-media"><video controls src="' . esc_url($media_file) . '" class="galigeo-slide-video"></video></div>';
                } else {
                    $output .= '<div class="galigeo-slide-media"><img src="' . esc_url($media_file) . '" alt="' . esc_attr($title) . '" class="galigeo-slide-image"></div>';
                }
            }


            $output .= '</div></div>';
        }

        $output .= '</div>';
        $output .= '<button class="galigeo-slider-nav galigeo-slider-prev">&lt;</button>';
        $output .= '<button class="galigeo-slider-nav galigeo-slider-next">&gt;</button>';
        $output .= '</div></div>';

        return $output;
    }
}
