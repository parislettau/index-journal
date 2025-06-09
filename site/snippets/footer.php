<!-- https://plugins.andkindness.com/seo/docs/get-started/installation-setup -->
<?php if ($page->template() === 'essay') snippet('scholarly-schema'); ?>
<?php snippet('seo/schemas'); ?>
<footer class="mt-24 px-4 py-12 border-t border-black text-sm text-black mt-[12px] p-[12px] text-[16px]">
    <div class="max-w-5xl mx-auto grid grid-cols-3 sm:grid-cols-3 gap-8 text-sm ">

        <!-- Column 1: Journal Info -->
        <div>
            <div class="font-semibold uppercase tracking-wide">Index Journal</div>
            <div class="mt-2">ISSN 2652-4740</div>
            <div class="mt-2">Published in Narrm, Australia </div>
        </div>

        <!-- Column 2: Key Policies -->
        <div>
            <div class="font-semibold uppercase tracking-wide">Information</div>
            <div><a href="/about/information#peer-review" class="hover:underline">Peer Review</a></div>
            <div><a href="/about/information#ethics" class="hover:underline">Publication Ethics</a></div>
            <div><a href="/about/information#open-access" class="hover:underline">Open Access & Licensing</a></div>
            <div><a href="/about/information#archiving" class="hover:underline">Archiving Policy</a></div>
            <div><a href="/about/information#indexing" class="hover:underline">Indexing & Discoverability</a></div>

        </div>

        <!-- Column 3: Contact + Legal -->
        <div>
            <div class="font-semibold uppercase tracking-wide">Contact</div>
            <div class="mt-2">General enquiries:
                <a href="mailto:editor@index-journal.org" class="underline">editor@index-journal.org</a>
            </div>
            <div class="mt-4 text-gray-600">© Index Press Inc.<br>Content licensed under <a href="https://creativecommons.org/licenses/by-nc-nd/4.0/" class="underline">CC BY-NC-ND 4.0</a></div>

        </div>

    </div>
</footer>

</body>

</html>