# Kirby 3 Robots.txt

![GitHub release](https://img.shields.io/github/release/bnomei/kirby3-robots-txt.svg?maxAge=1800) ![License](https://img.shields.io/github/license/mashape/apistatus.svg) ![Kirby Version](https://img.shields.io/badge/Kirby-3%2B-black.svg)

Manage the [robots.txt](https://developers.google.com/search/reference/robots_txt) from the Kirby config file.

This plugin is free but if you use it in a commercial project please consider to [make a donation 🍻](https://www.paypal.me/bnomei/0.5).

## Usage

The plugin generates automatic defaults for the starterkit. You do not have to enter them in the config file. But if you would it would look like this.

**defaults for starterkit**
```php
<?php
return [
    'bnomei.robots-txt.content' => null,
    'bnomei.robots-txt.sitemap' => null,
    'bnomei.robots-txt.groups' => [
        '*' => [ // user-agent
            'disallow' => [
                '/kirby',
                '/site',
            ],
            'allow' => [
                '/media',
            ]
        ]
    ]
];
```

**using a plain string**
```php
<?php
return [
    'bnomei.robots-txt.content' => 'user-agent: *
disallow: /kirby
disallow: /site
allow: /media',
];
```

**sitemap and multiple user-agents**
```php
<?php
return [
    'bnomei.robots-txt.sitemap' => 'sitemap.xml',
    'bnomei.robots-txt.groups' => [
        '*' => [
            'disallow' => [
                '/',
            ],
        ],
        'googlebot-images' => [
            'allow' => [
                '/media',
            ]
        ]
    ]
];
```

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/bnomei/kirby3-robots-txt/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.
