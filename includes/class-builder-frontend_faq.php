<?php
class FAQ_Block_Frontend {
    public function render($data) {
        $faqs = $data['faqs'] ?? [];
        
        if (empty($faqs)) {
            return ''; // Retourner rien si aucune FAQ n'est configurée
        }

        ob_start();
        ?>
        <div class="galigeo-faq-accordion">
            <?php foreach ($faqs as $index => $faq): ?>
                <div class="galigeo-faq-item">
                    <div class="galigeo-faq-question" role="button" aria-expanded="<?php echo $index === 0 ? 'true' : 'false'; ?>" tabindex="0">
                        <?php echo esc_html($faq['question'] ?? ''); ?>
                        <span class="galigeo-faq-icon" aria-hidden="true"><?php echo $index === 0 ? '-' : '+'; ?></span>
                    </div>
                    <div class="galigeo-faq-answer" style="<?php echo $index === 0 ? 'height: auto;' : 'height: 0; overflow: hidden;'; ?>">
                        <div class="galigeo-faq-answer-content">
                            <?php echo wp_kses_post($faq['answer'] ?? ''); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <script>
        document.addEventListener("DOMContentLoaded", function () {
            const faqItems = document.querySelectorAll(".galigeo-faq-item");

            faqItems.forEach((item, index) => {
                const question = item.querySelector(".galigeo-faq-question");
                const answer = item.querySelector(".galigeo-faq-answer");
                const content = item.querySelector(".galigeo-faq-answer-content");
                const icon = question.querySelector(".galigeo-faq-icon");

                // Initial setup for all items except the first
                if (index !== 0) {
                    answer.style.height = "0";
                    answer.style.overflow = "hidden";
                }

                answer.style.transition = "height 0.3s ease";

                question.addEventListener("click", function () {
                    const isOpen = question.getAttribute("aria-expanded") === "true";

                    // Fermer toutes les réponses
                    faqItems.forEach((i) => {
                        const otherAnswer = i.querySelector(".galigeo-faq-answer");
                        const otherQuestion = i.querySelector(".galigeo-faq-question");
                        const otherIcon = i.querySelector(".galigeo-faq-icon");

                        otherQuestion.setAttribute("aria-expanded", "false");
                        otherAnswer.style.height = "0";
                        otherIcon.textContent = "+";
                    });

                    // Basculer l'état de l'élément actuel
                    if (!isOpen) {
                        question.setAttribute("aria-expanded", "true");
                        answer.style.height = content.scrollHeight + "px"; // Ajuster à la hauteur de contenu
                        icon.textContent = "-";
                    }
                });

                // Support clavier : Toggle avec Enter ou Espace
                question.addEventListener("keydown", function (event) {
                    if (event.key === "Enter" || event.key === " ") {
                        event.preventDefault();
                        question.click();
                    }
                });
            });
        });
        </script>
        <style>
        .galigeo-faq-accordion {
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
            margin: 5rem auto;
            padding-top: 2.5rem;
            background-color: #fff;
        }

        .galigeo-faq-item {
            border-bottom: 1px solid #ccc;
        }

        .galigeo-faq-item:last-child {
            border-bottom: none;
        }

        .galigeo-faq-question {
            padding: 15px;
            background-color: #f9f9f9;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            outline: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: "Montserrat";
            font-size: 1.75rem;
            color: #03234D;
            /*margin-bottom: 0.5rem;*/
        }

        .galigeo-faq-question:hover,
        .galigeo-faq-question[aria-expanded="true"] {
            background-color: #f1f1f1;
        }

        .galigeo-faq-icon {
            font-size: 0.938rem;
            font-weight: bold;
            margin-left: 10px;
            background-color: #03234D;
            border-radius: 0.5rem;
            color: #fff;
            font-family: 'Montserrat';
            padding: 0.75rem 1.5rem;
        }

        .galigeo-faq-answer {
            height: 0; /* Par défaut, totalement replié */
            overflow: hidden; /* Masquer tout contenu débordant */
            border-top: 1px solid #eee;
            transition: height 0.3s ease; /* Transition pour l'effet de déroulement */
        }

        .galigeo-faq-answer-content {
            padding: 15px; /* Appliquer le padding interne ici */
            background-color: #fff;
            font-family: 'Montserrat';
            font-size: 1.25rem;
            color: #909090;
            height: 7.5rem;
            margin-bottom: 1.5rem;
        }

        .galigeo-faq-answer[style*="height: auto;"] {
            height: auto; /* Laisser la réponse ouverte pour le premier élément */
        }
        </style>
        <?php
        return ob_get_clean();
    }
}
