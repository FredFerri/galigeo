<?php
class Cas_Client_Block_Frontend {
    public function render($data) {
        $title = $data['title'] ?? '';
        $title_tag = $data['title_tag'] ?? 'h2';
        $clients = $data['clients'] ?? [];

        // Début de la sortie HTML
        $output = '<div class="galigeo-cas-client-block">';

        // Titre principal
        if ($title) {
            $output .= "<$title_tag class='galigeo-cas-client-title galigeo-title'>" . esc_html($title) . "</$title_tag>";
        }

        // Conteneur des cas clients
        $output .= '<div class="galigeo-cas-client-container">';

        // Parcourir les cas clients et les afficher
        foreach ($clients as $index => $client_id) {
            $client_post = get_post($client_id);
            if ($client_post) {
                // Récupération des informations du cas client
                $client_title = get_the_title($client_id);
                $client_content = wp_strip_all_tags($client_post->post_content); // Contenu principal sans balises HTML
                $client_excerpt = mb_strimwidth($client_content, 0, 60, '...'); // Limité à 60 caractères
                $client_permalink = get_permalink($client_id);
                $client_thumbnail = get_the_post_thumbnail_url($client_id, 'medium') ?: 'https://via.placeholder.com/300'; // Placeholder si pas d'image

                // Début du cas client
                $output .= '<div class="galigeo-cas-client-item">';
                $output .= '<div class="galigeo-cas-client-content">';

                // Image
                $output .= '<a href="' . esc_url($client_permalink) . '" class="galigeo-cas-client-image-link">';
                $output .= '<img src="' . esc_url($client_thumbnail) . '" alt="' . esc_attr($client_title) . '" class="galigeo-cas-client-image">';
                $output .= '</a>';

                // "Success story"
                $output .= '<p class="galigeo-cas-client-success-story">Success story</p>';

                // Titre
                $output .= '<h3 class="galigeo-cas-client-title">';
                $output .= '<a href="' . esc_url($client_permalink) . '">' . esc_html($client_title) . '</a>';
                $output .= '</h3>';

                // Extrait
                $output .= '<p class="galigeo-cas-client-excerpt">' . esc_html($client_excerpt) . '</p>';

                // Bouton "Lire la suite"
                $output .= '<a href="' . esc_url($client_permalink) . '" class="galigeo-cas-client-read-more-button">Lire la suite</a>';

                // Fin du contenu du cas client
                $output .= '</div></div>';
            }
        }

        // Fin du conteneur des cas clients
        $output .= '</div>';

        // Fin de la sortie HTML
        $output .= '</div>';

        return $output;
    }
}
