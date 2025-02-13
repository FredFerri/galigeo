<?php
class Heros_Cas_Client_Block_Frontend {
    public function render($data) {
        $block_id = $data['block_id'] ?? '';
        $title = $data['title'] ?? '';
        $title_tag = $data['title_tag'] ?? 'h2';
        $description = $data['description'] ?? '';
        $success_story = $data['success_story'] ?? '';
        $main_image = $data['main_image'] ?? '';
        $bg_type = $data['bg_type'] ?? 'color';
        $bg_value = $bg_type === 'color' ? ($data['bg_color'] ?? '#ffffff') : ($data['bg_image'] ?? '');
        $show_button = $data['show_button'] ?? false;
        $button_text = $data['button_text'] ?? '';
        $button_url = $data['button_link'] ?? '#';
        $button_color = $data['button_color'] ?? '#000000';

        // Style du background
        $bg_style = $bg_type === 'color' 
            ? "background-color: $bg_value;" 
            : "background-image: url('$bg_value'); background-size: cover; background-position: center;";

        // Début de la sortie HTML
        $output = '<div  id="' . esc_attr($block_id) . '" class="hero-cas-client" style="' . $bg_style . '">';
        $output .= '<div class="hero-container">';

        // Block de gauche : Success Story, Titre, et Description
        $output .= '<div class="hero-text">';
        if ($success_story) {
            $output .= '<p class="hero-success-story">' . esc_html($success_story) . '</p>';
        }
        if ($title) {
            $output .= "<$title_tag class='hero-title galigeo-title'>" . esc_html($title) . "</$title_tag>";
        }
        if ($description) {
            $output .= '<p class="hero-description">' . wp_kses_post($description) . '</p>';
        }
        // Bouton
        if ($show_button) {
            $output .= '<a href="' . esc_url($button_url) . '" style="background-color: ' . esc_attr($button_color) . ';" class="hero-button px-6 py-3 rounded-lg text-white font-semibold">' . esc_html($button_text) . '</a>';
        }        
        $output .= '</div>';

        // Block de droite : Image principale
        if ($main_image) {
            $output .= '<div class="hero-image">';
            $output .= '<img src="' . esc_url($main_image) . '" alt="Image Hero Cas Client">';
            $output .= '</div>';
        }

        // Fin de la sortie HTML
        $output .= '</div></div>';

        return $output;
    }
}
