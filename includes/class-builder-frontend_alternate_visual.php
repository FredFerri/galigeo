<?php
class Alternate_Visual_Block_Frontend {
    public function render($data) {
        var_dump($data);
        // Récupération des données enregistrées dans le backend
        $title = $data['av_title'] ?? '';
        $title_tag = $data['av_title_tag'] ?? 'h2';
        $subtitle = $data['av_subtitle'] ?? '';
        $paragraph = $data['av_paragraph'] ?? '';
        $font_size = $data['av_font_size'] ?? 'medium';
        $paragraph_color = $data['av_paragraph_color'] ?? '#000000';
        $background_type = $data['av_background_type'] ?? 'color';
        $background_value = $background_type === 'color' ? ($data['av_background_value'] ?? '#ffffff') : ($data['av_background_value'] ?? '');
        $file_url = $data['av_file'] ?? '';
        $position = $data['av_position'] ?? 'left';
        $show_button = $data['av_show_button'] ?? false;
        $button_text = $data['av_button_text'] ?? '';
        $button_url = $data['av_button_link'] ?? '#';
        $button_color = $data['av_button_color'] ?? '#000000';

        // Style de fond (couleur ou image)
        $background_style = $background_type === 'color'
            ? "background-color: $background_value;"
            : "background-image: url('$background_value'); background-size: cover; background-position: center;";

        // Container principal avec le background
        $output = '<div class="av_alternate_visual_block py-16 my-12" style="' . esc_attr($background_style) . '">';
        $output .= '<div class="container mx-auto px-4 flex space-around items-center">';

        // Bloc de gauche (image/vidéo/GIF)
        if ($file_url) {
            $output .= '<div class="av_left_block w-1/2">';
            if (preg_match('/\.(mp4|webm)$/', $file_url)) {
                $output .= '<video class="w-full h-auto" controls><source src="' . esc_url($file_url) . '" type="video/mp4"></video>';
            } else {
                $output .= '<img src="' . esc_url($file_url) . '" alt="Media" class="w-full h-auto av_media">';
            }
            $output .= '</div>';
        }

        // Bloc de droite (texte et bouton)
        $output .= '<div class="av_right_block w-1/2 flex flex-col items-start space-y-5">';

        // Titre
        if ($title) {
            $output .= "<$title_tag class='av_title text-3xl font-bold'>" . esc_html($title) . "</$title_tag>";
        }

        // Sous-titre
        if ($subtitle) {
            $output .= '<h3 class="av_subtitle text-2xl font-semibold">' . esc_html($subtitle) . '</h3>';
        }

        // Paragraphe
        if ($paragraph) {
            $output .= '<p class="av_paragraph" style="font-size: ' . esc_attr($font_size) . '; color: ' . esc_attr($paragraph_color) . ';">' . wp_kses_post($paragraph) . '</p>';
        }

        // Bouton
        if ($show_button) {
            $output .= '<a href="' . esc_url($button_url) . '" class="av_button px-6 py-3 rounded-lg text-white font-semibold" style="background-color: ' . esc_attr($button_color) . '; padding: 12px; border-radius: 10px;">' . esc_html($button_text) . '</a>';
        }

        $output .= '</div>'; // Fermeture du bloc de droite
        $output .= '</div>'; // Fermeture du container principal
        $output .= '</div>'; // Fermeture du container flex

        return $output;
    }
}
