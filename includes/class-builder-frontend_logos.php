<?php
class Logos_Carousel_Block_Frontend {
    public function render($data) {
        $logos = $data['lc_logos'] ?? [];
        $title = $data['lc_title'] ?? '';
        $title_tag = $data['lc_title_tag'] ?? 'h2';
        $background_type = $data['lc_background_type'] ?? 'color';
        $background_color = $data['lc_background_color'] ?? '#ffffff';
        $background_image = $data['lc_background_image'] ?? '';
        $black_white = !empty($data['lc_black_white']);
        
        if (empty($logos)) return '';
        
        $output = '<div class="galigeo-logo-carousel-wrapper" style="';
        if ($background_type === 'color') {
            $output .= "background-color: $background_color;";
        } elseif ($background_type === 'image') {
            $output .= "background-image: url('$background_image'); background-size: cover;";
        }
        $output .= '">';

        // Afficher le titre
        if ($title) {
            $output .= "<$title_tag class='galigeo-logo-carousel-title galigeo-title'>" . esc_html($title) . "</$title_tag>";
        }

        $output .= '<div class="galigeo-logo-carousel">';
        $output .= '<button class="galigeo-logo-carousel-nav galigeo-logo-carousel-prev">&larr;</button>';
        $output .= '<div class="galigeo-logo-carousel-container">';

        // Ajouter les logos
        foreach ($logos as $logo) {
            $output .= '<div class="galigeo-logo-carousel-slide">';
            $output .= '<img src="' . esc_url($logo) . '" alt="Logo" class="galigeo-logo-carousel-image' . 
                      ($black_white ? ' black-white' : '') . '">';
            $output .= '</div>';
        }

        $output .= '</div>';
        $output .= '<button class="galigeo-logo-carousel-nav galigeo-logo-carousel-next">&rarr;</button>';
        $output .= '</div></div>';

        return $output;
    }
}