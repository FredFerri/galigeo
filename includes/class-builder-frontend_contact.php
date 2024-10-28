<?php
class Contact_Block_Frontend {
    public function render($data) {
        var_dump($data);
        $title = $data['title'] ?? '';
        $title_tag = $data['title_tag'] ?? 'h2';
        $description = $data['description'] ?? '';
        $bg_type = $data['bg_type'] ?? 'color';
        $bg_value = $bg_type === 'color' ? ($data['bg_color'] ?? '#ffffff') : ($data['bg_image'] ?? '');
        $salesforce_code = $data['salesforce_code'] ?? '';

        $bg_style = $bg_type === 'color'
            ? "background-color: $bg_value;"
            : "background-image: url('$bg_value'); background-size: cover; background-position: center;";

        $output = '<div class="contact-block py-16 my-12" style="' . esc_attr($bg_style) . '">';
        $output .= '<div class="container mx-auto px-4">';

        if ($title) {
            $output .= "<$title_tag class='text-3xl font-bold mb-8'>" . esc_html($title) . "</$title_tag>";
        }

        if ($description) {
            $output .= '<p class="mb-8">' . wp_kses_post($description) . '</p>';
        }

        if ($salesforce_code) {
            $output .= '<div class="salesforce-form mb-8">' . wp_kses_post($salesforce_code) . '</div>';
        }

        $output .= '</div></div>';

        return $output;
    }
}
?>
