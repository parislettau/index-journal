<?php snippet('header') ?>

<form method="get" class="search-form">
  <label>
    Keywords
    <input type="search" aria-label="Search" name="q" value="<?= html($query) ?>">
  </label>
  <label>
    Author
    <input type="text" name="author" value="<?= html($author ?? '') ?>">
  </label>
  <label>
    Date
    <input type="text" name="date" value="<?= html($date ?? '') ?>" placeholder="YYYY or YYYY-MM-DD">
  </label>
  <label>
    Tags
    <input type="text" name="keywords" value="<?= html($keywords ?? '') ?>">
  </label>
  <input type="submit" value="Search">
</form>

<ul>
<?php foreach ($results as $result): ?>
  <li><a href="<?= $result->url() ?>"><?= $result->title() ?></a></li>
<?php endforeach ?>
</ul>

<?php if ($pagination->hasPages()): ?>
<nav class="pagination">
  <?php if ($pagination->hasPrevPage()): ?>
    <a href="<?= $pagination->prevPageUrl() ?>">Previous</a>
  <?php endif ?>
  <?php if ($pagination->hasNextPage()): ?>
    <a href="<?= $pagination->nextPageUrl() ?>">Next</a>
  <?php endif ?>
</nav>
<?php endif ?>

<?php snippet('footer') ?>
