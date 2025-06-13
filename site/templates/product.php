<?php
$color = $page->color();
snippet('books/header', ['color' => $color]) ?>

<?php $product = $page ?>

<main class="prose mx-auto max-w-3xl pt-24">
    <section class="title-block product-title" style="background-color: <?= $page->color() ?>">
        <h1 style="font-size:6vw"><span class="title"><?= $page->title() ?></span>
            <?php if ($page->subtitle()->isNotEmpty()) : ?><span class="subtitle"><?= smartypants($page->subtitle()->kti()) ?></span><?php endif ?>
            <?php if ($page->slug() != 'introduction') : ?><span class="author">

                    <ul class="editors" style="font-size:6vw">
                        <?php if ($product->editors()->isNotEmpty()) : ?>
                            <?php foreach ($product->editors()->split() as $editor) : ?>
                                <li class="editor" style="font-size:6vw"><span> <?= $editor ?></span></li>
                            <?php endforeach ?>
                        <?php else : ?>
                            <?php foreach ($product->authors()->split() as $author) : ?>
                                <li class="author" style="font-size:6vw"><span><?= $author ?></span></li>
                            <?php endforeach ?>
                        <?php endif ?>
                    </ul>

                </span><?php endif ?>
        </h1>
    </section>

    <ul class="product-container">


        <li class="text">
            <?= $product->description()->kt() ?>
            <?php if ($product->reviews()->isNotEmpty()) : ?>
                <h2>Reviews</h2>
                <?= $product->reviews()->kt() ?>
            <?php endif ?>
            <div class="layouts">
                <?php foreach ($page->layout()->toLayouts() as $layout) : ?>
                    <section class="grid gap-[14px] grid-cols-1 md:grid-cols-12" id="<?= $layout->id() ?>">
                        <?php foreach ($layout->columns() as $column) : ?>
                            <div class="column mb-[14px] md:col-span-<?= $column->span() ?>" style="--span:<?= $column->span() ?>; --columns:<?= $column->span() ?>">
                                <?php foreach ($column->blocks() as $block) : ?>
                                    <div id="<?= $block->id() ?>" class="block block-type-<?= $block->type() ?>">
                                        <?= $block ?>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        <?php endforeach ?>
                    </section>
                <?php endforeach ?>
            </div>
        </li>
        <li class="image ">

            <style>
                img {
                    max-width: 100%;
                }
            </style>
            <?php if ($image = $product->cover()->tofile()) : ?>

                <div class="cover-outer-wrapper">
                    <div class="cover-wrapper">
                        <div class="cover">
                            <img alt="<?= $image->alt() ?>" src="<?= $image->resize(300)->url() ?>" srcset="<?= $image->srcset() ?>" width="<?= $image->resize(500)->width() ?>" height="<?= $image->resize(500)->height() ?>" style="  height: 80%; margin: auto;">
                        </div>
                    </div>

                    <figcaption class="details">
                        <?= kirbytextinline($product->details()) ?>
                    </figcaption>
                    <figcaption>
                        A$<?php
                            $number = $product->price()->value();
                            echo number_format((float)$number, 2, '.', '');
                            ?>
                    </figcaption>
                    <style>
                        .add-to-cart {
                            /* color: #fff; */
                            color: black;
                            margin-top: 1rem;
                            border-color: transparent;
                            border-color: var(--borderColor-buttonPrimary, transparent);
                            /* background-color: black; */
                            background-color: white;

                            /* snipcart-base-button */
                            padding: 16px;
                            border: 1px solid;
                            border-radius: 4px;
                            cursor: pointer;
                            transition: .2s ease-out;
                            transition-property: color, border-color, background-color, box-shadow;
                            width: 100%;
                            /* width: 80%; */

                        }

                        .add-to-cart:hover {
                            color: #fff;
                            border-color: transparent;
                            background-color: black;
                            /* background-color: var(--bgColor-buttonPrimary-hover, #0d59f2); */
                            box-shadow: 0 10px 4px -8px rgb(0 0 0 / 50%);
                            box-shadow: var(--shadow-buttonPrimary-hover, 0 10px 4px -8px rgba(0, 0, 0, .5));
                        }

                        .add-to-cart-wrapper {
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        }

                        .add-to-cart-wrapper {
                            flex-grow: 1;
                            /* padding: 0 16px; */
                            font-family: Montserrat, sans-serif;
                            font-size: 14px;
                            font-weight: 500;
                        }
                    </style>
                    <button type="button" class="add-to-cart  is-icon-right snipcart-add-item" data-item-id="<?= $product->id() ?>" data-item-price="<?= $product->price() ?>" data-item-description="<?= $product->details() ?>" data-item-image="<?= $image->resize(300)->url() ?>" data-item-name="<?= $product->title() ?>" data-item-width="<?= $product->width() ?>" data-item-height="<?= $product->height() ?>" data-item-length="<?= $product->length() ?>" data-item-weight="<?= $product->weight() ?>" style="cursor:pointer;">
                        <div class="add-to-cart-wrapper">
                            <div class="add-to-cart-wrapper"> ADD TO CART </div>
                        </div>
                    </button>
                </div>


                <!-- product cover -->
                <!-- <figure style="opacity: 1" class="figuregrid ">
                    <span class="img " style="--w:4;--h:3;--background:#333333;background:#333333" data-contain="false">
                        <picture>
                            <source srcset="<?= $image->srcset('avif') ?>" type="image/avif">
                            <source srcset="<?= $image->srcset('webp') ?>" type="image/webp">
                            <img alt="<?= $image->alt() ?>" src="<?= $image->resize(300)->url() ?>" srcset="<?= $image->srcset() ?>" width="<?= $image->resize(500)->width() ?>" height="<?= $image->resize(500)->height() ?>" style="  height: 80%; margin: auto;">
                        </picture>
                    </span>
                </figure> -->






            <?php endif ?>




        </li>


    </ul>


</main>

<?php snippet('books/footer') ?>