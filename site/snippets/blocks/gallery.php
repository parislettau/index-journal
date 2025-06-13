<div class="grid gap-4">
    <div class="column" style="--columns: 12">
        <ul class="gallery grid grid-cols-3 gap-4">
            <?php foreach ($block->images()->toFiles() as $image) : ?>
                <li>
                    <a href="<?= $image->url() ?>" data-lightbox>
                        <figure class="img" style="--w:<?= $image->width() ?>;--h:<?= $image->height() ?>">
                            <?= $image ?>
                        </figure>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
</div>