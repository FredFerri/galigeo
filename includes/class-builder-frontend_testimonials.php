<?php
class Testimonials_Block_Frontend {
    public function render($data) {
        $block_id = $data['block_id'] ?? '';
        $testimonials = $data['testimonials'] ?? [];
        $background_image = $data['background_image'] ?? '';

        if (empty($testimonials)) {
            return '';
        }

        $bg_style = $background_image ? "background-image: url('$background_image'); background-size: cover; background-position: center;" : '';

        $output = '<div id="' . esc_attr($block_id) . '"  class="testimonials-slider-wrapper" style="' . esc_attr($bg_style) . '">';
        $output .= '<div class="testimonials-slider" style="--slide-count: ' . count($testimonials) . ';">';
        $output .= '<div class="testimonials-slider-container">';

        foreach ($testimonials as $index => $testimonial) {
            $name = $testimonial['name'] ?? '';
            $position = $testimonial['position'] ?? '';
            $quote = $testimonial['quote'] ?? '';
            $image = $testimonial['image'] ?? '';

            $output .= '<div class="testimonial-slide" data-index="' . $index . '">';
            $output .= '<div class="testimonial-slide-content">';

            // Image, nom, poste
            $output .= '<div class="testimonial-header">';
            if ($image) {
                $output .= '<div class="testimonial-image"><img src="' . esc_url($image) . '" alt="' . esc_attr($name) . '"></div>';
            }
            $output .= '<div class="testimonial-details">';
            if ($name) {
                $output .= '<div class="testimonial-name" style="font-size:1.25rem;">' . esc_html($name) . '</div>';
            }
            if ($position) {
                $output .= '<div class="testimonial-position">' . esc_html($position) . '</div>';
            }
            $output .= '</div></div>';

            // Citation
            if ($quote) {
                $output .= '<div class="testimonial-quote">&ldquo;' . esc_html($quote) . '&rdquo;</div>';
            }

            $output .= '</div></div>';
        }

        $output .= '</div>';
        $output .= '<button class="testimonials-slider-nav testimonials-slider-prev">&lt;</button>';
        $output .= '<button class="testimonials-slider-nav testimonials-slider-next">&gt;</button>';
        $output .= '</div></div>';

        return $output;
    }
}
