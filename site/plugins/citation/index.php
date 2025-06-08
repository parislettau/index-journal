<?php

function citationBibtex(Kirby\Cms\Page $page): string {
    $authors = [];
    foreach ($page->authors()->yaml() as $author) {
        $authors[] = $author['last_name'] . ', ' . $author['first_name'];
    }
    $authorsStr = implode(' and ', $authors);
    $title = $page->title()->value();
    if ($page->subtitle()->isNotEmpty()) {
        $title .= ': ' . $page->subtitle()->value();
    }
    $year = $page->parent()->issue_date()->toDate('Y');
    $doi = $page->doi()->value();
    $id = $page->slug();
    $bibtex = "@article{{$id},\n";
    $bibtex .= "  title={{" . $title . "}},\n";
    if (!empty($authorsStr)) {
        $bibtex .= "  author={{" . $authorsStr . "}},\n";
    }
    $bibtex .= "  journal={{" . $page->site()->title()->value() . "}},\n";
    $bibtex .= "  year={{" . $year . "}},\n";
    if (!empty($doi)) {
        $bibtex .= "  doi={{" . $doi . "}},\n";
    }
    $bibtex .= "  url={{" . $page->url() . "}}\n";
    $bibtex .= "}\n";
    return $bibtex;
}

function citationRis(Kirby\Cms\Page $page): string {
    $ris = [];
    $ris[] = 'TY  - JOUR';
    foreach ($page->authors()->yaml() as $author) {
        $ris[] = 'AU  - ' . $author['last_name'] . ', ' . $author['first_name'];
    }
    $title = $page->title()->value();
    if ($page->subtitle()->isNotEmpty()) {
        $title .= ': ' . $page->subtitle()->value();
    }
    $ris[] = 'TI  - ' . $title;
    $ris[] = 'T2  - ' . $page->site()->title()->value();
    $ris[] = 'PY  - ' . $page->parent()->issue_date()->toDate('Y');
    if ($page->doi()->isNotEmpty()) {
        $ris[] = 'DO  - ' . $page->doi()->value();
    }
    $ris[] = 'UR  - ' . $page->url();
    $ris[] = 'ER  -';
    return implode("\n", $ris) . "\n";
}

function citationChicago(Kirby\Cms\Page $page): string {
    $authors = [];
    foreach ($page->authors()->yaml() as $author) {
        $authors[] = $author['first_name'] . ' ' . $author['last_name'];
    }
    $authorsStr = implode(', ', $authors);

    $title = $page->title()->value();
    if ($page->subtitle()->isNotEmpty()) {
        $title .= ': ' . $page->subtitle()->value();
    }

    $journal = $page->site()->title()->value();
    $issueNum = $page->parent()->num();
    $year = $page->parent()->issue_date()->toDate('Y');

    $citation = trim($authorsStr);
    if ($citation !== '') {
        $citation .= '. ';
    }
    $citation .= '"' . $title . '." ' . $journal . ', no. ' . $issueNum . ' (' . $year . ')';
    if ($page->doi()->isNotEmpty()) {
        $citation .= '. https://doi.org/' . $page->doi()->value() . '.';
    } else {
        $citation .= '. ' . $page->url();
    }

    return $citation;
}

Kirby::plugin('custom/citation', []);
