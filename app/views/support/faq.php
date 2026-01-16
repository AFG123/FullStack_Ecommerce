<?php
$title = "FAQ - " . SITE_NAME;
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-10">
        <h2 class="text-center mb-4">Frequently Asked Questions</h2>

        <div class="accordion" id="faqAccordion">
            <?php foreach ($faqs as $index => $faq): ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading<?php echo $index; ?>">
                        <button class="accordion-button <?php echo $index > 0 ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $index; ?>" aria-expanded="<?php echo $index == 0 ? 'true' : 'false'; ?>" aria-controls="collapse<?php echo $index; ?>">
                            <?php echo htmlspecialchars($faq['question']); ?>
                        </button>
                    </h2>
                    <div id="collapse<?php echo $index; ?>" class="accordion-collapse collapse <?php echo $index == 0 ? 'show' : ''; ?>" aria-labelledby="heading<?php echo $index; ?>" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <?php echo nl2br(htmlspecialchars($faq['answer'])); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($faqs)): ?>
            <div class="text-center text-muted">
                <p>No FAQs available at the moment.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>