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

        // Ensemble de balises autorisées (excluant <script> pour un traitement spécial)
        $allowed_tags = [
            'iframe' => [
                'src'             => [],
                'width'           => [],
                'height'          => [],
                'frameborder'     => [],
                'allowfullscreen' => [],
                'loading'         => [],
            ],
            'div' => [
                'class' => [],
                'style' => [],
            ],
            'p' => [],
            'span' => [
                'class' => [],
            ],
            'a' => [
                'href'   => [],
                'title'  => [],
                'target' => [],
            ],
            'img' => [
                'src'    => [],
                'alt'    => [],
                'class'  => [],
                'width'  => [],
                'height' => [],
            ],
        ];

        // Extraire les scripts et les enregistrer
        $scripts = $this->extract_scripts($html_code);
        $sanitized_html = wp_kses($html_code, $allowed_tags); // Nettoyage du HTML sans les scripts

        // Construction de l'affichage du bloc
        $output = '<div class="import-html-block py-12 my-12" ' . $id_attr . ' style="' . esc_attr($background_style) . '">';

        // Affichage du titre
        if ($title) {
            $output .= "<$title_tag class='galigeo-html-title galigeo-title text-2xl font-bold mb-4 galigeo-bleu text-center'>" . esc_html($title) . "</$title_tag>";
        }

        // Affichage du code HTML
        if ($sanitized_html) {
            $output .= '<div class="galigeo-html-content w-3/4 m-auto">';
            $output .= $sanitized_html;
            $output .= '</div>';
        }

        $output .= '</div>';

        // Ajout des scripts en bas de page
        if (!empty($scripts)) {
            // $output .= '<script>';
            foreach ($scripts as $script) {
                $output .= $script . "\n";
            }
            // $output .= '</script>';
        }

        return $output;
    }

    /**
     * Extraire les balises <script> et retourner une liste des scripts trouvés.
     */
    private function extract_scripts(&$html) {
        $scripts = [];
        if (preg_match_all('/<script\b[^>]*>(.*?)<\/script>/is', $html, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $scripts[] = trim($match[0]); // Récupérer le script complet
                $html = str_replace($match[0], '', $html); // Supprimer le script du HTML principal
            }
        }
        return $scripts;
    }
}
