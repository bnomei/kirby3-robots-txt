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
            $this->processGroupsArray($groups);
        } elseif (is_string($groups)) {
            $this->txt[] = $groups;
        }

        return $this;
    }

    private function processGroupsArray(array $groups): void
    {
        foreach ($groups as $useragent => $group) {
            $this->txt[] = 'user-agent: '.$useragent;
            if (is_array($group)) {
                $this->processGroupFields($group);
            }
        }
    }

    private function processGroupFields(array $group): void
    {
        foreach ($group as $field => $values) {
            if (is_array($values)) {
                $this->processFieldValues($field, $values);
            }
        }
    }

    private function processFieldValues(string $field, array $values): void
    {
        foreach ($values as $value) {
            $this->txt[] = implode('', [$field, ': ', $value]);
        }
    }

    private function hasSitemapFromKnownPlugin(): bool
    {
        return count(array_filter([
            option('isaactopo.xmlsitemap.ignore') !== null,
            option('omz13.xmlsitemap.disable') === false,
            option('fabianmichael.meta.sitemap') === true,
            option('tobimori.seo.robots.active') === false,
            option('johannschopplich.helpers.sitemap.enable') === true && option('johannschopplich.helpers.robots.enable') === false,
            option('bnomei.feed.sitemap.enable') === true,
        ])) > 0;
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
