<?php
class Alternate_Visual_Block_Frontend {
    public function render($data) {
        // Récupération des données enregistrées dans le backend
        $block_id = $data['av_block_id'] ?? '';
        $title = $data['av_title'] ?? '';
        $title_tag = $data['av_title_tag'] ?? 'h2';
        $subtitle = $data['av_subtitle'] ?? '';
        $paragraph = $data['av_paragraph'] ?? '';
        $font_size = $data['av_font_size'] ?? 'medium';
        $paragraph_color = $data['av_paragraph_color'] ?? '#000000';
        $background_type = $data['av_background_type'] ?? 'color';
        $background_value = $background_type === 'color' ? ($data['av_background_value'] ?? '#ffffff') : ($data['av_background_value'] ?? '');
        $file_url = $data['av_file'] ?? '';
        $image_url = $data['av_inner_image'] ?? ''; // Nouvelle image à l'intérieur du bloc
        $image_alignment = $data['av_image_alignment'] ?? 'left'; // Nouvel alignement de l'image
        $show_button = $data['av_show_button'] ?? false;
        $button_text = $data['av_button_text'] ?? '';
        $button_url = $data['av_button_link'] ?? '#';
        $button_color = $data['av_button_color'] ?? '#000000';

        // Style de fond (couleur ou image)
        $background_style = $background_type === 'color'
            ? "background-color: $background_value;"
            : "background-image: url('$background_value'); background-size: cover; background-position: center;";

        // Détermine l'ordre des blocs (image à gauche ou à droite)
        $order_classes = $image_alignment === 'right' ? 'flex-row-reverse' : 'flex-row';

        // Container principal avec le background
        $output = '<div id="' . esc_attr($block_id) . '" class="av_alternate_visual_block py-16 my-12" style="' . esc_attr($background_style) . '">';
        $output .= '<div class="container mx-auto px-4 flex ' . esc_attr($order_classes) . ' items-center">';

        // Bloc d'image à l'intérieur (nouvelle image ajoutée)
        if ($image_url) {
            $output .= '<div class="av_image_block w-1/2 flex items-center justify-center">';
            $output .= '<img src="' . esc_url($image_url) . '" alt="Image" class="max-w-full max-h-full object-contain">';
            $output .= '</div>';
        }

        // Bloc de texte
        $output .= '<div class="av_text_block w-1/2 flex flex-col items-start space-y-5">';

        // Titre
        if ($title) {
            $output .= "<$title_tag class='av_title galigeo-title text-3xl font-bold'>" . esc_html($title) . "</$title_tag>";
        }

        // Sous-titre
        if ($subtitle) {
            $output .= '<h3 class="av_subtitle galigeo-subtitle text-2xl font-semibold">' . esc_html($subtitle) . '</h3>';
        }

        // Paragraphe avec la couleur dynamique
        if ($paragraph) {
            $output .= '<p class="av_paragraph" style="color: ' . esc_attr($paragraph_color) . ';">' . wp_kses_post($paragraph) . '</p>';
        }

        // Bouton avec couleur de fond dynamique
        if ($show_button) {
            $output .= '<a href="' . esc_url($button_url) . '" class="av_button px-6 py-3 rounded-lg text-white font-semibold" style="background-color: ' . esc_attr($button_color) . ';">' . esc_html($button_text) . '</a>';
        }

        $output .= '</div>'; // Fermeture du bloc de texte
        $output .= '</div>'; // Fermeture du container flex
        $output .= '</div>'; // Fermeture du container principal

        return $output;
    }
}
