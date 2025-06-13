<section class="menu-pane fixed top-0 left-0 w-screen z-[1000] p-4 bg-white shadow-[0px_15px_16px_0px_rgba(255,255,255,1)] overflow-scroll transition" <?php if ($page->template() == 'essay') : ?>style="background-color: rgb(<?= $page->parent()->issue_color() ?>); box-shadow: 0px 15px 16px 0px rgba(<?= $page->parent()->issue_color() ?>);" <?php endif ?><?php if ($page->template() == 'product') : ?> style="background-color: <?= $page->color() ?>; box-shadow: 0px 15px 16px 0px <?= $page->color() ?>;" <?php endif ?>>
    <h1 class="close">(close)</h1>

    <h1 style="display: flex;flex-direction: row;">
        <a href="/">INDEX JOURNAL</a>
        <?php foreach (page('issues')->children()->listed()->flip()->slice(0, 1) as $issue) : ?>
            <a href="<?= $issue->url() ?>" class="current-issue"><span>, Issue </span><span>No. </span><?= $issue->num() ?><span style="text-transform: uppercase;"> <?= $issue->title() ?></span></a>
        <?php endforeach ?>
    </h1>
    <h1>Issues</h1>
    <ul style="margin-top:0">
        <?php foreach ($site->find('issues')->children()->listed() as $subPage) : ?>
            <h1><a href="<?= $subPage->url() ?>">Issue No. <?= $subPage->num() ?> <span style="text-transform:uppercase"><?= $subPage->title() ?></span></a></h1>

        <?php endforeach ?>
    </ul>
    <h1>Special Issues</h1>
    <ul style="margin-top:0">
        <?php foreach ($site->find('special-issues')->children()->listed() as $subPage) : ?>
            <h1><a href="<?= $subPage->url() ?>"> <span style="text-transform:uppercase"><?= $subPage->title() ?></span></a></h1>

        <?php endforeach ?>
    </ul>

    <h1><a href="https://index-press.com/" target="_blank">Index Press</a></h1>
    <h1><a href="/about">About</a></h1>
    <h1><a href="<?= $site->url() ?>/emaj">EMAJ</a></h1>
</section>