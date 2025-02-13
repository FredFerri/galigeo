<?php
class Contact_Block_Frontend {
    public function render($data) {
        $block_id = $data['block_id'] ?? '';
        $title = $data['title'] ?? '';
        $title_tag = $data['title_tag'] ?? 'h2';
        $description = $data['description'] ?? '';
        $bg_type = $data['bg_type'] ?? 'color';
        $bg_value = $bg_type === 'color' ? ($data['bg_color'] ?? '#ffffff') : ($data['bg_image'] ?? '');
        $salesforce_code = $data['salesforce_code'] ?? '';

        $bg_style = $bg_type === 'color'
            ? "background-color: $bg_value;"
            : "background-image: url('$bg_value'); background-size: cover; background-position: center;";

        $output = '<div id="' . esc_attr($block_id) . '" class="contact-block py-16 my-12" style="' . esc_attr($bg_style) . '">';
        $output .= '<div class="container mx-auto px-4">';

        if ($title) {
            $output .= "<$title_tag class='galigeo-title text-3xl font-bold mb-8'>" . esc_html($title) . "</$title_tag>";
        }

        if ($description) {
            $output .= '<p class="mb-8">' . wp_kses_post($description) . '</p>';
        }

        if ($salesforce_code) {
            // Autoriser les balises nécessaires pour Salesforce Code (y compris iframe)
            $allowed_tags = array(
                'iframe' => array(
                    'src'             => true,
                    'width'           => true,
                    'height'          => true,
                    'frameborder'     => true,
                    'allow'           => true,
                    'allowfullscreen' => true,
                ),
                'div' => array(
                    'class' => true,
                    'id'    => true,
                    'style' => true,
                ),
                'p' => array(),
                'span' => array(
                    'class' => true,
                    'style' => true,
                ),
                'a' => array(
                    'href'   => true,
                    'target' => true,
                    'rel'    => true,
                ),
            );

            $output .= '<div class="salesforce-form mb-8">' . wp_kses($salesforce_code, $allowed_tags) . '</div>';
        }

        $output .= '</div></div>';

        return $output;
    }
}
?>
