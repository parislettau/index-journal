<?php
if (!isset($page)) return;

if (in_array($page->template()->name(), ['essay', 'special-issue-essay', 'emaj-essay'])) {
    $citation = citationChicago($page);
    ?>
    <div class="how-to-cite" style="margin-top:1rem;font-size:0.875rem;">
        <strong>How to cite this article:</strong> <span class="citation-text"><?= html($citation) ?></span>
    </div>
    <?php
}
?>
