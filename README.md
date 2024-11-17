# Kirby Robots.txt

![Release](https://flat.badgen.net/packagist/v/bnomei/kirby3-robots-txt?color=ae81ff)
![Downloads](https://flat.badgen.net/packagist/dt/bnomei/kirby3-robots-txt?color=272822)
[![Coverage](https://flat.badgen.net/codeclimate/coverage/bnomei/kirby3-robots-txt)](https://codeclimate.com/github/bnomei/kirby3-robots-txt)
[![Maintainability](https://flat.badgen.net/codeclimate/maintainability/bnomei/kirby3-robots-txt)](https://codeclimate.com/github/bnomei/kirby3-robots-txt)
[![Discord](https://flat.badgen.net/badge/discord/bnomei?color=7289da)](https://discordapp.com/users/bnomei)


Manage a virtual [robots.txt](https://developers.google.com/search/reference/robots_txt) from the Kirby config file.


## Installation

- unzip [master.zip](https://github.com/bnomei/kirby3-robots-txt/archive/master.zip) as folder `site/plugins/kirby3-robots-txt` or
- `git submodule add https://github.com/bnomei/kirby3-robots-txt.git site/plugins/kirby3-robots-txt` or
- `composer require bnomei/kirby3-robots-txt`

## Staging Server? Debug Mode = Disallow all

Setting the global Kirby `debug` configuration to `true` will prevent all indexing for every user agent. This is particularly useful on staging servers, but you may also want to consider it for XML sitemaps and RSS feeds, among other things.

> [!WARNING]  
> This means if you have Kirby's debug mode enabled in production, all search engines will be blocked from indexing your site!

## Adding Sitemap Link to Robots.txt

This plugin will add the sitemap link **automatically** to the virtual `robots.txt` file for most available SEO plugins. In that case, you can skip setting the `bnomei.robots-txt.sitemap` config value to `sitemap.xml`.

## Setup

The plugin generates automatic defaults for the starterkit. You do not have to enter them in the config file. But if you would, it would look like this.

**defaults for starterkit**
```php
<?php
return [
    'bnomei.robots-txt.content' => null, // string or callback
    'bnomei.robots-txt.sitemap' => null, // null (aka. AUTOMATIC) or string or callback
    'bnomei.robots-txt.groups' => [ // array or callback
        '*' => [ // user-agent
            'disallow' => [
                '/kirby/',
                '/site/',
                '/cdn-cgi/',
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
disallow: /cdn-cgi/
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

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/bnomei/kirby3-robots-txt/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.
