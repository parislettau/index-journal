<?php
/**
 * Build ScholarlyArticle schema for an essay page
 */

if ($page->template() !== 'essay') {
    return;
}

$article = $page->schema('ScholarlyArticle')
    ->name($page->title()->value())
    ->headline($page->title()->value())
    ->url($page->url())
    ->identifier($page->doi()->value());

if ($page->parent()->issue_date()->isNotEmpty()) {
    $article->datePublished($page->parent()->issue_date()->toDate('Y-m-d'));
}

if ($page->abstract()->isNotEmpty()) {
    $article->description($page->abstract()->kirbytextinline());
}

if ($page->keywords()->isNotEmpty()) {
    $article->keywords($page->keywords()->value());
}

if ($page->authors()->isNotEmpty()) {
    foreach ($page->authors()->toStructure() as $author) {
        $person = schema('Person')
            ->name($author->first_name() . ' ' . $author->last_name())
            ->givenName($author->first_name()->value())
            ->familyName($author->last_name()->value());
        if ($author->orcid()->isNotEmpty()) {
            $person->identifier($author->orcid()->value());
        }
        $article->author($person);
    }
} elseif ($page->author()->isNotEmpty()) {
    $article->author($page->author()->value());
}
