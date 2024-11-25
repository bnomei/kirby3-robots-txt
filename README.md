# Kirby Robots.txt

[![Kirby 5](https://flat.badgen.net/badge/Kirby/5?color=ECC748)](https://getkirby.com)
![PHP 8.2](https://flat.badgen.net/badge/PHP/8.2?color=4E5B93&icon=php&label)
![Release](https://flat.badgen.net/packagist/v/bnomei/kirby3-robots-txt?color=ae81ff&icon=github&label)
![Downloads](https://flat.badgen.net/packagist/dt/bnomei/kirby3-robots-txt?color=272822&icon=github&label)
[![Coverage](https://flat.badgen.net/codeclimate/coverage/bnomei/kirby3-robots-txt?icon=codeclimate&label)](https://codeclimate.com/github/bnomei/kirby3-robots-txt)
[![Maintainability](https://flat.badgen.net/codeclimate/maintainability/bnomei/kirby3-robots-txt?icon=codeclimate&label)](https://codeclimate.com/github/bnomei/kirby3-robots-txt/issues)
[![Discord](https://flat.badgen.net/badge/discord/bnomei?color=7289da&icon=discord&label)](https://discordapp.com/users/bnomei)
[![Buymecoffee](https://flat.badgen.net/badge/icon/donate?icon=buymeacoffee&color=FF813F&label)](https://www.buymeacoffee.com/bnomei)

Installing the plugin is enough to get a virtual [robots.txt](https://developers.google.com/search/reference/robots_txt) file.

## Installation

- unzip [master.zip](https://github.com/bnomei/kirby3-robots-txt/archive/master.zip) as folder `site/plugins/kirby3-robots-txt` or
- `git submodule add https://github.com/bnomei/kirby3-robots-txt.git site/plugins/kirby3-robots-txt` or
- `composer require bnomei/kirby3-robots-txt`

## Zero-Configuration

The plugin will work out of the box without the need to configure anything. It will generate a virtual `robots.txt` file with the following content.

```txt
User-agent: *
Disallow: /kirby/
Disallow: /site/
Disallow: /cdn-cgi/
Allow: /media/
```

## Active debug mode will block indexing by search engines

> [!WARNING]
> Setting the global Kirby `debug` configuration to `true` will prevent all indexing for every user agent (all Search Engines).

## Link to Sitemap.xml

This plugin will add the link to your `sitemap.xml` **automatically** for most available SEO plugins. In that case, you can skip setting the `bnomei.robots-txt.sitemap` config value to `sitemap.xml`.

## Optional Manual Configuration

The plugin generates automatic defaults optimized for the official [Kirby Starterkit](https://github.com/getkirby/starterkit). You do not have to enter any of them in the config file. But if you would, they would look like this.

**defaults for Kirby Starterkit**
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
