<?php

declare(strict_types=1);

namespace Bnomei;

use Kirby\Toolkit\A;

final class Robotstxt
{
    /**
     * @var string[]
     */
    private $txt;
    /**
     * @var array
     */
    private $options;

    /**
     * Robotstxt constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->txt = [];

        $defaults = [
            'debug' => option('debug'),
            'content' => option('bnomei.robots-txt.content'),
            'groups' => option('bnomei.robots-txt.groups'),
            'sitemap' => option('bnomei.robots-txt.sitemap'),
        ];
        $this->options = array_merge($defaults, $options);

        foreach ($this->options as $key => $call) {
            if (is_callable($call)) {
                $this->options[$key] = $call();
            }
        }

        $this->addContent(A::get($this->options, 'content'));
        $this->addGroups(A::get($this->options, 'groups'));
        $this->addSitemap(A::get($this->options, 'sitemap'));
    }

    /**
     * @return array|null
     */
    public function toArray(): ?array
    {
        return count($this->txt) ? $this->txt : null;
    }

    /**
     * @return string|null
     */
    public function toTxt(): ?string
    {
        return count($this->txt) ? implode(PHP_EOL, $this->txt) . PHP_EOL : null;
    }

    /**
     * @param null $content
     *
     * @return Robotstxt
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
     * @param null $groups
     *
     * @return Robotstxt
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
                $this->txt[] = 'user-agent: ' . $useragent;
                foreach ($group as $field => $values) {
                    foreach ($values as $value) {
                        $this->txt[] = $field . ': ' . $value;
                    }
                }
            }
        } else {
            $this->txt[] = (string) $groups;
        }
        return $this;
    }

    /**
     * @param null $sitemap
     *
     * @return Robotstxt
     */
    private function addSitemap($sitemap = null): Robotstxt
    {
        if (! $sitemap) {
            return $this;
        }

        // @codeCoverageIgnoreStart
        if (option('omz13.xmlsitemap.disable') === false) {
            $this->txt[] = 'sitemap: ' . url('/sitemap.xml');
            return $this;
        }
        // @codeCoverageIgnoreEnd

        $this->txt[] = 'sitemap: ' . url($sitemap);
        return $this;
    }
}
