<?php


return function ($site) {
    $query = get('q');

    if ($query) {
        $results = $site
            ->index()
            ->listed()
            ->search($query, 'title|text|author|authors|keywords')
            ->paginate(20);

        return [
            'query'      => $query,
            'results'    => $results,
            'pagination' => $results->pagination(),
        ];
    }

    return [
        'query'      => $query,
        'results'    => null,
        'pagination' => null,
    ];
};
