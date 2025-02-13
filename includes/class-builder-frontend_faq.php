<?php
class FAQ_Block_Frontend {
    public function render($data) {
        // var_dump($data);
        $faqs = $data['faqs'] ?? [];
        $section_title = $data['faq_section_title'] ?? '';
        $block_id = $data['faq_block_id'] ?? '';

        if (empty($faqs)) {
            return ''; // Retourner rien si aucune FAQ n'est configurée
        }

        ob_start();
        ?>
        <div id="<?php echo esc_attr($block_id); ?>" class="galigeo-faq-block">
            <!-- Titre de la section -->
            <?php if ($section_title): ?>
                <h2 class="galigeo-faq-title text-2xl font-bold mb-6"><?php echo esc_html($section_title); ?></h2>
            <?php endif; ?>

            <!-- Accordéon FAQ -->
            <div class="galigeo-faq-accordion">
                <?php foreach ($faqs as $index => $faq): ?>
                    <div class="galigeo-faq-item">
                        <div class="galigeo-faq-question" role="button" aria-expanded="<?php echo $index === 0 ? 'true' : 'false'; ?>" tabindex="0">
                            <?php echo esc_html($faq['question'] ?? ''); ?>
                            <div class="galigeo-faq-icon-block"><span class="galigeo-faq-icon" aria-hidden="true"><?php echo $index === 0 ? '-' : '+'; ?></span></div>
                        </div>
                        <div class="galigeo-faq-answer" style="<?php echo $index === 0 ? 'height: auto;' : 'height: 0; overflow: hidden;'; ?>">
                            <div class="galigeo-faq-answer-content">
                                <?php echo wp_kses_post($faq['answer'] ?? ''); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <script>
        document.addEventListener("DOMContentLoaded", function () {
            const faqItems = document.querySelectorAll(".galigeo-faq-item");

            faqItems.forEach((item, index) => {
                const question = item.querySelector(".galigeo-faq-question");
                const answer = item.querySelector(".galigeo-faq-answer");
                const content = item.querySelector(".galigeo-faq-answer-content");
                const icon = question.querySelector(".galigeo-faq-icon");

                answer.style.transition = "height 0.3s ease";

                question.addEventListener("click", function () {
                    const isOpen = question.getAttribute("aria-expanded") === "true";

                    faqItems.forEach((i) => {
                        const otherAnswer = i.querySelector(".galigeo-faq-answer");
                        const otherQuestion = i.querySelector(".galigeo-faq-question");
                        const otherIcon = i.querySelector(".galigeo-faq-icon");

                        otherQuestion.setAttribute("aria-expanded", "false");
                        otherAnswer.style.height = "0";
                        otherIcon.textContent = "+";
                        question.classList.remove('galigeo-faq-question_open');
                        // item.style.borderBottomRightRadius = "1rem";
                        // item.style.borderBottomLeftRadius = "1rem"; 
                        // question.style.borderBottomRightRadius = "1rem";
                        // question.style.borderBottomLeftRadius = "1rem";                                               
                    });

                    if (!isOpen) {
                        question.setAttribute("aria-expanded", "true");
                        let calculatedHeight = content.scrollHeight + 25;
                        answer.style.height = calculatedHeight + "px";
                        icon.textContent = "-";
                        question.classList.add('galigeo-faq-question_open');
                        // item.style.borderBottomRightRadius = "0 !important";
                        // item.style.borderBottomLeftRadius = "0 !important";
                        // question.style.borderBottomRightRadius = "0 !important";
                        // question.style.borderBottomLeftRadius = "0 !important";
                    }
                });

                question.addEventListener("keydown", function (event) {
                    if (event.key === "Enter" || event.key === " ") {
                        event.preventDefault();
                        question.click();
                    }
                });
            });
        });
        </script>
        <?php
        return ob_get_clean();
    }
}
