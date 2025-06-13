<?php snippet('books/header', ['color' => 'white']) ?>
<main class="pt-[5.6em] pr-4 md:pr-1 pb-[5.6em]">
    <div>
        <ul class="pb-[5.6em]">
            <?= $site->about()->kt() ?>
        </ul>
        <ul class="grid grid-cols-3 md:grid-cols-1 gap-4 text-[length:var(--font-small)] relative">

            <?php foreach ($page->children()->listed() as $product) : ?>
                <li>
                    <a href="<?= $product->url() ?>" class="group relative overflow-hidden block">
                        <?php if ($image = $product->cover()->tofile()) : ?>
                            <figure class="figuregrid transition-none opacity-100 block group-hover:opacity-0">
                                <span class="img" style="--w:4;--h:3;--background:black;background:black" data-contain="false">
                                    <picture>
                                        <source srcset="<?= $image->srcset('webp') ?>" type="image/webp">
                                        <img alt="<?= $image->alt() ?>" src="<?= $image->url() ?>" srcset="<?= $image->srcset('default') ?>" style="  height: 80%; margin: auto;">
                                    </picture>
                                </span>
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-center z-20 invisible group-hover:visible">
                                <figcaption class="text ">
                                    <p><?= $product->title() ?></p>
                                    <?php
                                    if ($product->editors()->isNotEmpty()) {
                                        $names = $product->editors()->split();
                                        $label = 'Edited by ';
                                    } else {
                                        $names = $product->authors()->split();
                                        $label = 'by ';
                                    }
                                    $last = array_pop($names);
                                    $list = $last;
                                    if (count($names)) {
                                        $list = implode(', ', $names) . ' and ' . $last;
                                    }
                                    ?>
                                    <p class="text-base mt-2"><?= $label . $list ?></p>
                                </figcaption>
                            </div>
                        <?php endif ?>

                    </a>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
</main>

<?php snippet('books/footer') ?>
