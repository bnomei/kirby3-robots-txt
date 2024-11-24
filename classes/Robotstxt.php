<?php

declare(strict_types=1);

namespace Bnomei;

use Kirby\Toolkit\A;

final class Robotstxt
{
    public function __construct(
        private array $txt = [],
        private array $options = []
    ) {
        $defaults = [
            'debug' => option('debug'),
            'content' => option('bnomei.robots-txt.content'),
            'groups' => option('bnomei.robots-txt.groups'),
            'sitemap' => option('bnomei.robots-txt.sitemap'),
        ];
        $this->options = array_merge($defaults, $this->options);

        foreach ($this->options as $key => $call) {
            if ($call instanceof \Closure) {
                $this->options[$key] = $call();
            }
        }

        $this->addContent(A::get($this->options, 'content'));
        $this->addGroups(A::get($this->options, 'groups'));
        $this->addSitemap(A::get($this->options, 'sitemap'));
    }

    public function toArray(): ?array
    {
        return count($this->txt) ? $this->txt : null;
    }

    public function toTxt(): ?string
    {
        return count($this->txt) ? implode(PHP_EOL, $this->txt).PHP_EOL : null;
    }

    /**
     * @param  null  $content
     */
    private function addContent($content = null): Robotstxt
    {
        if (! $content) {
            return $this;
        }
        $this->txt[] = (string) $content;

        return $this;
    }

    /**
     * @param  null  $groups
     */
    private function addGroups($groups = null): Robotstxt
    {
        if (! $groups) {
            return $this;
        }
        if (A::get($this->options, 'debug')) {
            $groups = ['*' => ['disallow' => ['/']]];
        }
        if (! is_array($groups) && ! is_string($groups) && is_callable($groups)) {
            $groups = $groups();
        }
        if (is_array($groups)) {
            foreach ($groups as $useragent => $group) {
                $this->txt[] = 'user-agent: '.$useragent;
                foreach ($group as $field => $values) {
                    foreach ($values as $value) {
                        $this->txt[] = $field.': '.$value;
                    }
                }
            }
        } else {
            $this->txt[] = (string) $groups;
        }

        return $this;
    }

    private function hasSitemapFromKnownPlugin(): bool
    {
        if (option('omz13.xmlsitemap.disable') === false) {
            return true;
        }
        if (option('fabianmichael.meta.sitemap') === true) {
            return true;
        }
        if (option('tobimori.seo.robots.active') === false) {
            return true;
        }
        if (option('johannschopplich.helpers.sitemap.enable') === true && option('johannschopplich.helpers.robots.enable') === false) {
            return true;
        }
        if (option('kirbyzone.sitemapper.customMap') instanceof \Closure) {
            return true;
        }
        if (option('bnomei.feed.sitemap.enable') === true) {
            return true;
        }

        return false;
    }

    /**
     * @param  null  $sitemap
     */
    private function addSitemap($sitemap = null): Robotstxt
    {
        // @codeCoverageIgnoreStart
        if ($this->hasSitemapFromKnownPlugin()) {
            $this->txt[] = 'sitemap: '.url('/sitemap.xml');

            return $this;
        }
        // @codeCoverageIgnoreEnd

        if (! $sitemap) {
            return $this;
        }

        $this->txt[] = 'sitemap: '.url($sitemap);

        return $this;
    }
}
