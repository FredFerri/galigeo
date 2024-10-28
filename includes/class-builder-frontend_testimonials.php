<?php
class Testimonials_Block_Frontend {
    public function render($data) {
        $testimonials = $data['testimonials'] ?? [];
        if (empty($testimonials)) {
            return '';
        }

        $output = '<div class="testimonials-slider-wrapper">';
        $output .= '<div class="testimonials-slider" style="--slide-count: ' . count($testimonials) . ';">';
        $output .= '<div class="testimonials-slider-container">';

        foreach ($testimonials as $index => $testimonial) {
            $name = $testimonial['name'] ?? '';
            $position = $testimonial['position'] ?? '';
            $quote = $testimonial['quote'] ?? '';
            $image = $testimonial['image'] ?? '';

            $output .= '<div class="testimonial-slide" data-index="' . $index . '">';
            $output .= '<div class="testimonial-slide-content">';

            // Affichage de l'image, du nom et du poste
            $output .= '<div class="testimonial-header">';
            if ($image) {
                $output .= '<div class="testimonial-image"><img src="' . esc_url($image) . '" alt="' . esc_attr($name) . '"></div>';
            }
            $output .= '<div class="testimonial-details">';
            if ($name) {
                $output .= '<div class="testimonial-name">' . esc_html($name) . '</div>';
            }
            if ($position) {
                $output .= '<div class="testimonial-position">' . esc_html($position) . '</div>';
            }
            $output .= '</div></div>'; // Fin du header

            // Affichage de la citation
            if ($quote) {
                $output .= '<div class="testimonial-quote">&ldquo;' . esc_html($quote) . '&rdquo;</div>';
            }

            $output .= '</div></div>'; // Fin du contenu et de la slide
        }

        $output .= '</div>';
        $output .= '<button class="testimonials-slider-nav testimonials-slider-prev">&lt;</button>';
        $output .= '<button class="testimonials-slider-nav testimonials-slider-next">&gt;</button>';     
        $output .= '</div></div>';

        return $output;
    }
}
