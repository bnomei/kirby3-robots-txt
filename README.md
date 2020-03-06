# Kirby 3 Robots.txt

![Release](https://flat.badgen.net/packagist/v/bnomei/kirby3-robots-txt?color=ae81ff)
![Downloads](https://flat.badgen.net/packagist/dt/bnomei/kirby3-robots-txt?color=272822)
[![Build Status](https://flat.badgen.net/travis/bnomei/kirby3-robots-txt)](https://travis-ci.com/bnomei/kirby3-robots-txt)
[![Coverage Status](https://flat.badgen.net/coveralls/c/github/bnomei/kirby3-robots-txt)](https://coveralls.io/github/bnomei/kirby3-robots-txt) 
[![Maintainability](https://flat.badgen.net/codeclimate/maintainability/bnomei/kirby3-robots-txt)](https://codeclimate.com/github/bnomei/kirby3-robots-txt) 
[![Twitter](https://flat.badgen.net/badge/twitter/bnomei?color=66d9ef)](https://twitter.com/bnomei)


Manage the [robots.txt](https://developers.google.com/search/reference/robots_txt) from the Kirby config file.

## Similar Plugin

- [kirby3-wellknown](https://github.com/omz13/kirby3-wellknown)

## Commerical Usage

This plugin is free but if you use it in a commercial project please consider to 
- [make a donation 🍻](https://www.paypal.me/bnomei/3) or
- [buy me ☕](https://buymeacoff.ee/bnomei) or
- [buy a Kirby license using this affiliate link](https://a.paddle.com/v2/click/1129/35731?link=1170)

## Installation

- unzip [master.zip](https://github.com/bnomei/kirby3-robots-txt/archive/master.zip) as folder `site/plugins/kirby3-robots-txt` or
- `git submodule add https://github.com/bnomei/kirby3-robots-txt.git site/plugins/kirby3-robots-txt` or
- `composer require bnomei/kirby3-robots-txt`

## Setup

The plugin generates automatic defaults for the starterkit. You do not have to enter them in the config file. But if you would it would look like this.

> TIP: If you are using the [kirby3-xmlsitemap Plugin](https://github.com/omz13/kirby3-xmlsitemap) your sitemap will be automatically added to the `robots.txt` file. You can skip setting the `bnomei.robots-txt.sitemap` config value to `sitemap.xml`.

**defaults for starterkit**
```php
<?php
return [
    'bnomei.robots-txt.content' => null, // string or callback
    'bnomei.robots-txt.sitemap' => null, // string or callback
    'bnomei.robots-txt.groups' => [ // array or callback
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
];
```

**using a plain string**
```php
<?php
return [
    'bnomei.robots-txt.content' => 'user-agent: *
disallow: /kirby/
disallow: /site/
allow: /media/',
];
```

**using a callback**
```php
<?php
return [
    'bnomei.robots-txt.content' => function() {
        return site()->myRobotsTxtContentField()->value();
    },
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
                '/media/',
            ]
        ]
    ]
];
```

## Staging Server? Debug Mode = Disallow all

Since v1.3.0 when you set the global Kirby `debug` config to `true` the plugin will disallow all indexing for all user-agents. This is especially usefull on a staging server but you could consider xml-sitemap and rss-feed among other things as well.

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/bnomei/kirby3-robots-txt/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.
