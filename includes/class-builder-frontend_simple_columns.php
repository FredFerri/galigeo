<?php
class Simple_Columns_Block_Frontend {
    public function render($data) {
        // Commence le container principal
        $output = '<div class="galigeo-columns-block mx-auto my-12 px-4 flex justify-between">';
        $output .= '<div class="galigeo-columns-subblock flex justify-between">';
        
        // Parcours chaque colonne
        foreach ($data['columns'] as $column) {
            $background_type = $column['background_type'] ?? 'color';
            $background_style = '';

            if ($background_type === 'image' && !empty($column['background_image'])) {
                $background_style = 'background-image: url(' . esc_url($column['background_image']) . '); background-size: cover; background-position: center;';
            } elseif ($background_type === 'color' && !empty($column['background_color'])) {
                $background_style = 'background-color: ' . esc_attr($column['background_color']) . ';';
            }

            // Ouverture du bloc de la colonne
            $output .= '<div class="galigeo-column p-4" style="' . esc_attr($background_style) . '">';

            // Affichage de l'image si disponible
            if (!empty($column['image'])) {
                $output .= '<img src="' . esc_url($column['image']) . '" alt="Colonne Image" class="galigeo-column-img mb-4 w-full h-auto">';
            }

            // Affichage du titre
            if (!empty($column['title'])) {
                $output .= '<' . esc_html($column['title_tag']) . ' class="galigeo-column-title galigeo-title text-2xl font-bold mb-4">' . esc_html($column['title']) . '</' . esc_html($column['title_tag']) . '>';
            }

            // Affichage de la description
            if (!empty($column['description'])) {
                $output .= '<p style="font-size:1.2rem; color: gray;" class="galigeo-column-desc">' . wp_kses_post($column['description']) . '</p>';
            }

            // Fermeture du bloc de la colonne
            $output .= '</div>';
        }

        // Fermeture du container principal
        $output .= '</div></div>';
        return $output;
    }
}
?>
