<?php

namespace Bnomei;

final class Robotstxt
{
    /**
     * @var array
     */
    private $txt;
    /**
     * @var array
     */
    private $options;

    /**
     * Robotstxt constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->txt = [];

        $this->options = [];
        $this->options['content'] = option('bnomei.robots-txt.content');
        $this->options['groups'] = option('bnomei.robots-txt.groups');
        $this->options['sitemap'] = option('bnomei.robots-txt.sitemap');
        $this->options['debug'] = option('debug');
        $this->options = array_merge($this->options, $options);

        $this->addContent(\Kirby\Toolkit\A::get($this->options, 'content'));
        $this->addGroups(\Kirby\Toolkit\A::get($this->options, 'groups'));
        $this->addSitemap(\Kirby\Toolkit\A::get($this->options, 'sitemap'));
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
        return count($this->txt) ? implode(PHP_EOL, $this->txt) : null;
    }

    /**
     * @param null $content
     * @return Robotstxt
     */
    private function addContent($content = null): Robotstxt
    {
        if (! $content) {
            return $this;
        }
        if (is_callable($content)) {
            $content = $content();
        }
        $this->txt[] = (string)$content;
        return $this;
    }

    /**
     * @param null $groups
     * @return Robotstxt
     */
    private function addGroups($groups = null): Robotstxt
    {
        if (! $groups) {
            return $this;
        }
        if (\Kirby\Toolkit\A::get($this->options, 'debug')) {
            $groups = ['*' => ['disallow' => ['/']]];
        }
        if (is_callable($groups)) {
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
            $this->txt[] = (string)$groups;
        }
        return $this;
    }

    /**
     * @param null $sitemap
     * @return Robotstxt
     */
    private function addSitemap($sitemap = null): Robotstxt
    {
        if ($sitemap) {
            if (is_callable($sitemap)) {
                $sitemap = $sitemap();
            }
            $this->txt[] = 'sitemap: ' . url($sitemap);
        } elseif (option('omz13.xmlsitemap.disable') === false) {
            $this->txt[] = 'sitemap: ' . url('/sitemap.xml');
        }
        return $this;
    }
}
