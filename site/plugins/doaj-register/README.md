# DOAJ Register

This Kirby plugin submits metadata for an essay to the [Directory of Open Access Journals](https://doaj.org/).
It collects the required fields from the page, shows a confirmation view and
uploads the data to DOAJ's API when confirmed.

## Usage

1. Enter your DOAJ API key in the **Site** settings within the Kirby Panel.
   You can also customise the API endpoint if required.

2. In the Panel open an essay and click **Submit to DOAJ**. Review the displayed
   metadata and confirm the submission.

The route `/submit-doaj/<page-id>` accepts `GET` and `POST` requests.
`GET` renders the confirmation page; a `POST` with `confirm=1` performs the
upload.

To submit all essays of an issue at once, open an issue page in the Panel and
click **Submit DOAJ Batch**. The route `/submit-doaj-bulk/<issue-id>` works the
same way and uploads all descendant essays in one request using DOAJ's bulk API.

