<?php snippet('header') ?>

<form method="get" class="search-form">
  <label>
    Search
    <input type="search" aria-label="Search" name="q" value="<?= html($query) ?>">
  </label>
  <input type="submit" value="Search">
</form>

<?php if ($results): ?>
  <ul>
  <?php foreach ($results as $result): ?>
    <li><a href="<?= $result->url() ?>"><?= $result->title() ?></a></li>
  <?php endforeach ?>
  </ul>

  <?php if ($pagination && $pagination->hasPages()): ?>
  <nav class="pagination">
    <?php if ($pagination->hasPrevPage()): ?>
      <a href="<?= $pagination->prevPageUrl() ?>">Previous</a>
    <?php endif ?>
    <?php if ($pagination->hasNextPage()): ?>
      <a href="<?= $pagination->nextPageUrl() ?>">Next</a>
    <?php endif ?>
  </nav>
  <?php endif ?>
<?php endif ?>

<?php snippet('footer') ?>
