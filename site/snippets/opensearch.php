<?php
/**
 * @var Kirby\Cms\Site $site
 * @var string $queryUrl
 */
?>
<?='<?xml version="1.0" encoding="utf-8"?>'?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
  <ShortName><?= xml($site->title()) ?></ShortName>
  <Description><?= xml($site->description()->or($site->title())) ?></Description>
  <Url type="text/html" method="get" template="<?= xml($queryUrl) ?>"/>
</OpenSearchDescription>
