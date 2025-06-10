<!-- https://plugins.andkindness.com/seo/docs/get-started/installation-setup -->
<?php if ($page->template() === 'essay') snippet('scholarly-schema'); ?>
<?php snippet('seo/schemas'); ?>
<footer class="border-t border-black text-sm text-black font-medium mt-[100px] mr-[6%] ml-4 mb-0 pb-[100px] overflow-hidden z-1000 relative w-[750px] clear-both">
    <div class="max-w-5xl mx-auto grid grid-cols-1 sm:grid-cols-3 gap-8 text-sm ">

        <!-- Column 1: Journal Info -->
        <div>
            <div class="uppercase tracking-wide">Index Journal</div>
            <div class="mt-2">ISSN 2652-4740</div>
            <div class="mt-2">Published in Narrm, Australia </div>
        </div>

        <!-- Column 2: Key Policies -->
        <div>
            <div class=" uppercase tracking-wide">Information</div>

            <?php foreach ($site->footer()->pages()->toPages() as $page): ?>
                <div>
                    <a href="<?= $page->url() ?>" class="hover:underline">
                        <?= html($page->title()) ?>
                    </a>
                </div>
            <?php endforeach ?>

        </div>

        <!-- Column 3: Contact + Legal -->
        <div>
            <div class=" uppercase tracking-wide">Contact</div>
            <a href="mailto:editor@index-journal.org" class="">editor@index-journal.org</a>
            <div>
                <a href="https://newsletter.index-press.com/subscription/form" class="">Email newsletter</a>

            </div>

            <div class="mt-4 text-gray-600">© Index Press Inc.<br><a href="https://creativecommons.org/licenses/by-nc-nd/4.0/" class="underline">CC BY-NC-ND 4.0</a></div>

        </div>

    </div>
</footer>

</body>

</html>