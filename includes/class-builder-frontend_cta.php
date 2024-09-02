<?php
class Call_To_Action_Block_Frontend {
    public function render($data) {
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

        $output = '<div class="galigeo-cta py-16 my-12" style="' . $bg_style . '">';
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