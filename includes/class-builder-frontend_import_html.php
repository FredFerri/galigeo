<?php
class Import_HTML_Block_Frontend {
    public function render($data) {
        $title = $data['html_title'] ?? '';
        $title_tag = $data['html_title_tag'] ?? 'h2';
        $html_code = $data['html_code'] ?? '';
        $block_id = $data['html_id'] ?? '';
        $background_type = $data['html_background_type'] ?? 'color';
        $background_value = $background_type === 'color' ? ($data['html_background_color'] ?? '#ffffff') : ($data['html_background_image'] ?? '');

        // Style du background (couleur ou image)
        $background_style = $background_type === 'color'
            ? "background-color: $background_value;"
            : "background-image: url('$background_value'); background-size: cover; background-position: center;";
                   
        // ID du bloc
        $id_attr = $block_id ? 'id="' . esc_attr($block_id) . '"' : '';

        // Construction de l'affichage du bloc
        $output = '<div class="import-html-block py-12 my-12" ' . $id_attr . ' style="' . esc_attr($background_style) . '">';   
        
        // Affichage du titre
        if ($title) {
            $output .= "<$title_tag class='galigeo-html-title text-2xl font-bold mb-4 galigeo-bleu text-center'>" . esc_html($title) . "</$title_tag>";
        }


        // Affichage du code HTML
        if ($html_code) {
            $output .= '<div class="galigeo-html-content w-3/4 m-auto">';
            $output .= wp_kses_post($html_code);
            $output .= '</div>';
        }

        $output .= '</div>';

        return $output;
    }
}
