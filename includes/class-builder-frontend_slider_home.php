<?php
class Slider_Home_Block_Frontend {
    public function render($data) {
        $slides = $data['slides'] ?? [];
        $block_id = $data['block_id'] ?? '';
        if (count($slides) === 0) return '';

        $output = '<div id="' . esc_attr($block_id) . '" class="galigeo-slider-home-wrapper">';
        $output .= '<div class="galigeo-slider-home">';
        $output .= '<div class="galigeo-slider-home-container">';

        foreach ($slides as $index => $slide) {
            $block_id = $slide['block_id'] ?? '';
            $bg_type = $slide['bg_type'] ?? 'color';
            $bg_value = $bg_type === 'color' ? ($slide['bg_color'] ?? '#ffffff') : ($slide['bg_image'] ?? '');
            $title = $slide['title'] ?? '';
            $subtitle = $slide['subtitle'] ?? ''; // Ajout du sous-titre
            $title_tag = $slide['title_tag'] ?? 'h2';
            $button_text = $slide['button_text'] ?? '';
            $button_url = $slide['button_url'] ?? '';
            $button_color = $slide['button_color'] ?? '#03234D'; // Couleur par défaut modifiée
            $image = $slide['extra_image'] ?? ''; // Ajout de l'image

            $output .= '<div id="' . esc_attr($block_id) . '" class="galigeo-slide-home" data-index="' . $index . '" style="' . ($bg_type === 'color' ? "background-color: $bg_value;" : "background-image: url('$bg_value');") . '">';
            $output .= '<div class="galigeo-slide-home-content">';

            // Titre
            if ($title) {
                $output .= "<$title_tag class='galigeo-slide-home-title galigeo-title'>" . esc_html($title) . "</$title_tag>";
            }

            // Sous-titre
            if ($subtitle) {
                $output .= '<p class="galigeo-slide-home-subtitle galigeo-subtitle">' . esc_html($subtitle) . '</p>';
            }

            // Bouton
            if ($button_text && $button_url) {
                $output .= '<a href="' . esc_url($button_url) . '" class="galigeo-slide-home-button" style="background-color: ' . esc_attr($button_color) . ';">' . esc_html($button_text) . '</a>';
            }

            // Image
            if ($image) {
                $output .= '<img src="' . esc_url($image) . '" alt="Slide image" class="galigeo-slide-home-extra-image">';
            }

            $output .= '</div></div>';
        }

        $output .= '</div>'; // .galigeo-slider-container

        // Ajouter des boutons si plus d'un slide
        if (count($slides) > 1) {
            $output .= '<button class="galigeo-slider-home-nav galigeo-slider-home-prev">&lt;</button>';
            $output .= '<button class="galigeo-slider-home-nav galigeo-slider-home-next">&gt;</button>';
        }
        else {
            $output .= '<button class="hidden galigeo-slider-home-nav galigeo-slider-home-prev">&lt;</button>';
            $output .= '<button class="hidden galigeo-slider-home-nav galigeo-slider-home-next">&gt;</button>';            
        }

        $output .= '</div></div>'; // .galigeo-slider-wrapper

        return $output;
    }
}
