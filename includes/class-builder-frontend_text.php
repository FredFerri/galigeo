<?php
class Text_Block_Frontend {
    public function render($data) {
        $title = $data['title'] ?? '';
        $title_tag = $data['title_tag'] ?? 'h2';
        $subtitle = $data['subtitle'] ?? '';
        $subtitle_tag = $data['subtitle_tag'] ?? 'h3';
        $description = $data['description'] ?? '';
        $description_font_size = $data['description_font_size'] ?? '16';
        $description_color = $data['description_color'] ?? '#000000';
        $bg_color = $data['bg_color'] ?? '#ffffff';
        $buttons = $data['buttons'] ?? [];

        $output = '<div class="galigeo-text-block py-16" style="background-color: ' . esc_attr($bg_color) . ';">';
        $output .= '<div class="galigeo-text-container mx-auto px-4">';

        // Titre
        if ($title) {
            $output .= "<$title_tag class='galigeo-text-title galigeo-title text-3xl font-bold mb-4'>" . esc_html($title) . "</$title_tag>";
        }

        // Sous-titre
        if ($subtitle) {
            $output .= "<$subtitle_tag class='galigeo-text-subtitle galigeo-subtitle text-2xl font-semibold mb-4'>" . esc_html($subtitle) . "</$subtitle_tag>";
        }

        // Description
        if ($description) {
            $output .= '<p class="galigeo-text-description mb-8" style="font-size: ' . esc_attr($description_font_size) . 'px; color: ' . esc_attr($description_color) . ';">' . wp_kses_post($description) . '</p>';
        }

        // Boutons
        if (!empty($buttons)) {
            $output .= '<div class="galigeo-text-boutons flex justify-center space-x-4">';
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
