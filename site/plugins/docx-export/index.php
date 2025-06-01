<?php

use Kirby\Cms\Response;

Kirby::plugin('custom/docx-export', [
    'routes' => [
        [
            'pattern' => 'download-docx/(:all)',
            'method'  => 'GET',
            'action'  => function (string $id) {
                if (!kirby()->user()) {
                    return new Response('Unauthorized', 'text/plain', 403);
                }

                if (!$page = page($id)) {
                    return new Response('Page not found', 'text/plain', 404);
                }

                $allowed = ['essay', 'special-issue-essay', 'emaj-essay'];
                if (!in_array($page->template()->name(), $allowed, true)) {
                    return new Response('Invalid page type', 'text/plain', 400);
                }

                // Build HTML
                $html = '<h1>' . $page->title()->esc() . '</h1>';
                if ($page->subtitle()->isNotEmpty()) {
                    $html .= '<h2>' . $page->subtitle()->kirbytextinline() . '</h2>';
                }
                if ($page->author()->isNotEmpty()) {
                    $html .= '<p>' . $page->author()->esc() . '</p>';
                }
                $html .= $page->text()->kirbytext();
                if ($page->bios()->isNotEmpty()) {
                    $html .= '<div class="bios">' . $page->bios()->kirbytext() . '</div>';
                }
                if ($page->bibilography()->isNotEmpty()) {
                    $html .= '<h2>Bibliography</h2>' . $page->bibilography()->kirbytext();
                }
                if ($page->selected_bibilography()->isNotEmpty()) {
                    $html .= '<h2>Selected Bibliography</h2>';
                    $html .= $page->headnote()->kirbytext();
                    foreach ($page->selected_bibilography()->toStructure() as $section) {
                        $html .= '<h3>' . $section->heading()->esc() . '</h3>';
                        $html .= $section->bibliography()->kirbytext();
                    }
                }

                $tmp = tempnam(sys_get_temp_dir(), 'docx');
                $htmlFile = $tmp . '.html';
                $docxFile = $tmp . '.docx';
                file_put_contents($htmlFile, $html);

                $cmd = 'pandoc ' . escapeshellarg($htmlFile) . ' -o ' . escapeshellarg($docxFile);
                exec($cmd, $out, $ret);
                if ($ret !== 0 || !file_exists($docxFile)) {
                    return new Response('Error creating DOCX', 'text/plain', 500);
                }

                return Response::download(
                    $docxFile,
                    $page->slug() . '.docx',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                );
            }
        ]
    ]
]);
