<?php
class Cas_Client_Block_Frontend {
    public function render($data) {
        $block_id = $data['block_id'] ?? '';
        $title = $data['title'] ?? '';
        $title_tag = $data['title_tag'] ?? 'h2';
        $clients = $data['clients'] ?? [];

        // Début de la sortie HTML
        $output = '<div class="galigeo-cas-client-block py-16 my-12">';

        // Titre principal
        if ($title) {
            $output .= "<$title_tag class='galigeo-cas-client-section-title galigeo-title'>" . esc_html($title) . "</$title_tag>";
        }

        // Conteneur des cas clients
        $output .= '<div  id="' . esc_attr($block_id) . '" class="galigeo-cas-client-container">';

        // Parcourir les cas clients et les afficher
        foreach ($clients as $index => $client_id) {
            $client_post = get_post($client_id);
            if ($client_post) {
                // Récupération des informations du cas client
                $client_title = get_the_title($client_id);
                $client_content = wp_strip_all_tags($client_post->post_content);
                // L'extrait affiché est soit le texte entré dans le champ "extrait" soit les 20 premiers caractères du contenu de l'article
                $client_excerpt = $client_post->post_excerpt ?: wp_trim_words($client_post->post_content, 200, '...');
                $client_thumbnail = get_the_post_thumbnail_url($client_id, 'medium') ?: 'https://via.placeholder.com/300';

                // Récupération des champs personnalisés
                $client_subtitle = get_post_meta($client_id, '_cas_client_subtitle', true) ?: 'Success story';
                $client_button_text = get_post_meta($client_id, '_cas_client_button_text', true) ?: 'Lire la suite';
                $client_button_url = get_post_meta($client_id, '_cas_client_button_url', true) ?: '';

                // Début du cas client
                $output .= '<div class="galigeo-cas-client-item">';
                $output .= '<div class="galigeo-cas-client-content">';

                // Image
                $output .= '<a href="' . esc_url($client_button_url) . '" class="galigeo-cas-client-image-link">';
                $output .= '<img src="' . esc_url($client_thumbnail) . '" alt="' . esc_attr($client_title) . '" class="galigeo-cas-client-image">';
                $output .= '</a>';

                $output .= '<div class="galigeo-cas-client-block-txt">';
                // Sous-titre
                $output .= '<p class="galigeo-cas-client-success-story">' . esc_html($client_subtitle) . '</p>';

                // Titre
                $output .= '<h3 class="galigeo-cas-client-title">';
                $output .= '<a href="' . esc_url($client_button_url) . '">' . esc_html($client_title) . '</a>';
                $output .= '</h3>';

                // Extrait
                $output .= '<p class="galigeo-cas-client-excerpt">' . esc_html($client_excerpt) . '</p>';

                // Bouton personnalisé
                $output .= '<a href="' . esc_url($client_button_url) . '" class="galigeo-cas-client-read-more-button">';
                $output .= esc_html($client_button_text);
                $output .= '</a>';

                // Fin du contenu du cas client
                $output .= '</div></div></div>';
            }
        }

        // Fin du conteneur des cas clients
        $output .= '</div>';

        // Fin de la sortie HTML
        $output .= '</div>';

        return $output;
    }
}
