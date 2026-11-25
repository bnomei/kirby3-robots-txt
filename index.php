<?php

declare(strict_types=1);
use Bnomei\Robotstxt;
use Kirby\Http\Response;

@include_once __DIR__.'/vendor/autoload.php';

Kirby::plugin('bnomei/robots-txt', [
    'options' => [
        'content' => null,
        'sitemap' => null,
        'groups' => [
            '*' => [ // user-agent
                'disallow' => [
                    '/kirby/',
                    '/site/',
                    '/cdn-cgi/', // cloudflare. https://developers.cloudflare.com/fundamentals/get-started/reference/cdn-cgi-endpoint/
                ],
                'allow' => [
                    '/media/',
                ],
            ],
        ],
    ],
    'routes' => [
        [
            'pattern' => 'robots.txt',
            'method' => 'GET',
            'action' => function () {
                $txt = (new Robotstxt)->toTxt();
                if ($txt) {
                    return new Response($txt, 'text/plain', 200);
                }

                return kirby()->site()->visit(
                    kirby()->site()->errorPage() ?? 'error'
                );
            },
        ],
    ],
]);
