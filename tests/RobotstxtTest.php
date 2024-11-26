<?php

require_once __DIR__.'/../vendor/autoload.php';

use Bnomei\Robotstxt;

test('construct', function () {
    $robotstxt = new Robotstxt(['debug' => false]);
    expect($robotstxt)->toBeInstanceOf(Robotstxt::class);
});
test('to array', function () {
    $robotstxt = new Robotstxt(['debug' => false]);
    expect($robotstxt->toArray())->toBeArray();
});
test('to txt', function () {
    $robotstxt = new Robotstxt(['debug' => false]);
    expect($robotstxt->toTxt())->toBeString();
});
test('disallow all on debug', function () {
    $robotstxt = new Robotstxt(['debug' => true]);
    expect($robotstxt->option('debug'))->toBeTrue()
        ->and($robotstxt->toTxt())->toContain('disallow: /'.PHP_EOL);
});
test('add content', function () {
    $robotstxt = new Robotstxt(['content' => '#Test Content']);
    expect($robotstxt->toTxt())->toStartWith('#Test Content'.PHP_EOL);

    $robotstxt = new Robotstxt(['content' => null]);
    expect($robotstxt->toTxt())->toStartWith('user-agent');

    $robotstxt = new Robotstxt(['content' => function () {
        return '# Callable';
    }]);
    expect($robotstxt->toTxt())->toStartWith('# Callable'.PHP_EOL);
});
test('add groups', function () {
    $robotstxt = new Robotstxt(['debug' => false, 'groups' => null]);
    expect($robotstxt->toTxt())->toBeNull();

    $robotstxt = new Robotstxt(
        [
            'debug' => false,
            'groups' => function () {
                return [
                    '*' => [ // user-agent
                        'disallow' => [
                            '/kirby/',
                            '/site/',
                        ],
                        'allow' => [
                            '/media/',
                        ],
                    ],
                ];
            },
        ]
    );
    $txt = $robotstxt->toTxt();
    expect($txt)->toMatch('/user-agent: \*\ndisallow: \/kirby\/\ndisallow: \/site\/\nallow: \/media\//');

    $robotstxt = new Robotstxt(['debug' => false, 'groups' => "user-agent: *\ndisallow: /panel/\n"]);
    expect($robotstxt->toTxt())->toMatch('/user-agent: \*\ndisallow: \/panel\/\n/'.PHP_EOL);

    $robotstxt = new Robotstxt(['debug' => true, 'groups' => "user-agent: *\ndisallow: /panel/\n"]);
    $this->assertDoesNotMatchRegularExpression('/user-agent: \*\ndisallow: \/panel\/\n/'.PHP_EOL, $robotstxt->toTxt());
});
test('add sitemap', function () {
    $robotstxt = new Robotstxt(['sitemap' => '/sitemap.xml']);
    expect($robotstxt->toTxt())->toMatch('/sitemap: .*\/sitemap.xml/');

    $robotstxt = new Robotstxt(['sitemap' => function () {
        return '/sitemap.xml';
    }]);
    expect($robotstxt->toTxt())->toMatch('/sitemap: .*\/sitemap.xml/');
});
