<?php
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
                    $txt = [];
                    if ($str = option('bnomei.robots-txt.content')) {
                        if (is_callable($str)) {
                            $str = $str();
                        }
                        $txt[] = $str;
                    }

                    if ($groups = option('bnomei.robots-txt.groups')) {
                        if (is_callable($groups)) {
                            $groups = $groups();
                        }
                        if (is_array($groups)) {
                            foreach ($groups as $useragent => $group) {
                                $txt[] = 'user-agent: ' . $useragent;
                                foreach ($group as $field => $values) {
                                    foreach ($values as $value) {
                                        $txt[] = $field . ': ' . $value;
                                    }
                                }
                            }
                        } else {
                            $txt[] = $groups;
                        }
                    }

                    if ($sitemap = option('bnomei.robots-txt.sitemap')) {
                        if (is_callable($sitemap)) {
                            $sitemap = $sitemap();
                        }
                        $txt[] = 'sitemap: ' . url($sitemap);
                    }

                    $txt = implode(PHP_EOL, $txt).PHP_EOL;
                    if (strlen($txt) > 0) {
                        return new \Kirby\Http\Response($txt, 'text/plain', 200);
                    }

                    return kirby()->site()->visit(
                        kirby()->site()->errorPage()
                    );
                }
            ]
        ]
    ]);
