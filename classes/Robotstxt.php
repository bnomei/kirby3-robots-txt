<?php

declare(strict_types=1);

namespace Bnomei;

use Kirby\Toolkit\A;

final class Robotstxt
{
    public function __construct(
        private array $options = [],
        private array $txt = [],
    ) {
        $defaults = [
            'debug' => option('debug'),
            'content' => option('bnomei.robots-txt.content'),
            'groups' => option('bnomei.robots-txt.groups'),
            'sitemap' => option('bnomei.robots-txt.sitemap'),
        ];
        $this->options = array_merge($defaults, $options);

        foreach ($this->options as $key => $call) {
            if ($call instanceof \Closure) {
                $this->options[$key] = $call();
            }
        }

        $this->addContent($this->option('content'))
            ->addGroups($this->option('groups'))
            ->addSitemap($this->option('sitemap'));
    }

    public function option(string $key): mixed
    {
        return A::get($this->options, $key);
    }

    public function toArray(): ?array
    {
        return count($this->txt) ? $this->txt : null;
    }

    public function toTxt(): ?string
    {
        return count($this->txt) ? implode(PHP_EOL, $this->txt).PHP_EOL : null;
    }

    private function addContent(mixed $content = null): self
    {
        if (empty($content)) {
            return $this;
        }
        if (is_string($content)) {
            $this->txt[] = $content;
        }

        return $this;
    }

    private function addGroups(mixed $groups = null): self
    {
        if (empty($groups)) {
            return $this;
        }
        if ($this->option('debug')) {
            $groups = ['*' => ['disallow' => ['/']]];
        }
        if (is_array($groups)) {
            foreach ($groups as $useragent => $group) {
                $this->txt[] = 'user-agent: '.$useragent;
                if (! is_array($group)) {
                    continue;
                }
                foreach ($group as $field => $values) {
                    if (! is_array($values)) {
                        continue;
                    }
                    foreach ($values as $value) {
                        $this->txt[] = implode('', [$field, ': ', $value]);
                    }
                }
            }
        } elseif (is_string($groups)) {
            $this->txt[] = $groups;
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

    private function addSitemap(mixed $sitemap = null): self
    {
        // @codeCoverageIgnoreStart
        if ($this->hasSitemapFromKnownPlugin()) {
            $this->txt[] = 'sitemap: '.url('/sitemap.xml');

            return $this;
        }
        // @codeCoverageIgnoreEnd

        if (! is_string($sitemap)) {
            return $this;
        }

        $this->txt[] = 'sitemap: '.url($sitemap);

        return $this;
    }
}
