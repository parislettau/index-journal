<?php

use Kirby\Toolkit\Str;

return function ($site) {
    $query = get('q');
    $author = get('author');
    $date = get('date');
    $keywords = get('keywords');

    $results = $site->index()->listed();

    if ($author) {
        $authorLower = Str::lower($author);
        $results = $results->filter(function ($page) use ($authorLower) {
            if ($page->author()->isNotEmpty() && Str::contains(Str::lower($page->author()->value()), $authorLower)) {
                return true;
            }
            if ($page->authors()->isNotEmpty()) {
                foreach ($page->authors()->yaml() as $a) {
                    $name = strtolower(($a['first_name'] ?? '') . ' ' . ($a['last_name'] ?? ''));
                    if (str_contains($name, $authorLower)) {
                        return true;
                    }
                }
            }
            return false;
        });
    }

    if ($date) {
        $results = $results->filter(function ($page) use ($date) {
            $dates = [];
            if ($page->date()->isNotEmpty()) {
                $dates[] = $page->date()->toDate('Y-m-d');
            }
            if ($page->pub_date()->isNotEmpty()) {
                $dates[] = $page->pub_date()->toDate('Y-m-d');
            }
            if ($page->parent() && $page->parent()->issue_date()->isNotEmpty()) {
                $dates[] = $page->parent()->issue_date()->toDate('Y-m-d');
            }
            foreach ($dates as $d) {
                if (Str::startsWith($d, $date)) {
                    return true;
                }
            }
            return false;
        });
    }

    if ($keywords) {
        $filter = array_map('strtolower', Str::split($keywords));
        $results = $results->filter(function ($page) use ($filter) {
            $pageKeywords = array_map('strtolower', $page->keywords()->split(','));
            return count(array_intersect($filter, $pageKeywords)) > 0;
        });
    }

    if ($query) {
        $results = $results->search($query, 'title|text|author|authors|keywords');
    }

    $results = $results->paginate(20);

    return [
        'query' => $query,
        'results' => $results,
        'pagination' => $results->pagination(),
        'author' => $author,
        'date' => $date,
        'keywords' => $keywords
    ];
};
