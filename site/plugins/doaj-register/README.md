# DOAJ Register

This Kirby plugin submits metadata for an essay to the [Directory of Open Access Journals](https://doaj.org/).
It collects the required fields from the page, shows a confirmation view and
uploads the data to DOAJ's API when confirmed.

## Usage

1. Provide your DOAJ API key in `site/config/config.php`:

```php
return [
    'doaj.apiKey' => env('DOAJ_API_KEY'),
    // optional: 'doaj.apiUrl' => 'https://doaj.org/api/articles'
];
```

2. In the Panel open an essay and click **Submit to DOAJ**. Review the displayed
   metadata and confirm the submission.

The route `/submit-doaj/<page-id>` accepts `GET` and `POST` requests.
`GET` renders the confirmation page; a `POST` with `confirm=1` performs the
upload.

