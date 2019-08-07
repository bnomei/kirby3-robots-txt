<?php

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('bnomei/robots-txt', [
    'options' => [
        'content' => null,
        'sitemap' => null,
        'groups' => [
            '*' => [ // user-agent
                'disallow' => [
                    '/kirby/',
                    '/site/',
                ],
                'allow' => [
                    '/media/',
                ]
            ]
        ]
    ],
    'routes' => [
        [
            'pattern' => 'robots.txt',
            'method' => 'GET',
            'action' => function () {
                if ($txt = (new \Bnomei\Robotstxt())->toTxt()) {
                    return new \Kirby\Http\Response($txt, 'text/plain', 200);
                }
                return kirby()->site()->visit(
                    kirby()->site()->errorPage()
                );
            }
        ]
    ]
]);
