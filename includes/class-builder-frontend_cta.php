<?php
class Call_To_Action_Block_Frontend {
    public function render($data) {
        $block_id = $data['block_id'] ?? '';
        $title = $data['title'] ?? '';
        $title_tag = $data['title_tag'] ?? 'h2';
        $description = $data['description'] ?? '';
        $description_font_size = $data['description_font_size'] ?? '16';
        $description_color = $data['description_color'] ?? '#000000';
        $image_url = $data['cta_inner_image'] ?? ''; // Nouvelle image à l'intérieur du bloc
        $bg_type = $data['bg_type'] ?? 'color';
        $bg_value = $bg_type === 'color' ? ($data['bg_color'] ?? '#ffffff') : ($data['bg_image'] ?? '');
        $buttons = $data['buttons'] ?? [];

        // Définir le style de fond
        $bg_style = $bg_type === 'color' 
            ? "background-color: $bg_value;" 
            : "background-image: url('$bg_value'); background-size: cover; background-position: center;";

        // Début de la sortie HTML
        $output = '<div id="' . esc_attr($block_id) . '" class="galigeo-cta-block py-16 my-12" style="' . $bg_style . '">';
        $output .= '<div class="cta-container flex justify-between items-center max-w-screen-xl mx-auto">';

        // Bloc de gauche : Titre, description, boutons
        $output .= '<div class="cta-text w-full md:w-1/2">';

        if ($title) {
            $output .= "<$title_tag class='text-3xl font-bold mb-4 cta-title'>" . esc_html($title) . "</$title_tag>";
        }

        if ($description) {
            $output .= '<p class="cta-description mb-6" style="font-size: ' . esc_attr($description_font_size) . 'px; color: ' . esc_attr($description_color) . ';">' . wp_kses_post($description) . '</p>';
        }

        if (!empty($buttons)) {
            $output .= '<div class="cta-buttons flex space-x-4">';
            foreach ($buttons as $button) {
                $button_text = $button['text'] ?? '';
                $button_url = $button['url'] ?? '';
                $button_color = $button['color'] ?? '#000000';
                $output .= '<a href="' . esc_url($button_url) . '" class="rounded-lg font-semibold text-white" style="background-color: ' . esc_attr($button_color) . ';">' . esc_html($button_text) . '</a>';
            }
            $output .= '</div>';
        }

        $output .= '</div>'; // Fin bloc de gauche

        // Bloc de droite : Image
        if (!empty($image_url)) {
            $output .= '<div class="cta-image w-full md:w-1/2 text-right">';
            $output .= '<img src="' . esc_url($image_url) . '" alt="CTA Image" class="inline-block max-w-full h-auto rounded">';
            $output .= '</div>';
        }

        $output .= '</div>'; // Fin du conteneur flex
        $output .= '</div>'; // Fin du bloc principal

        return $output;
    }
}
