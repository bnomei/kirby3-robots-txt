<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Bnomei\Robotstxt;
use PHPUnit\Framework\TestCase;

final class RobotstxtTest extends TestCase
{
    public function testConstruct()
    {
        $robotstxt = new Robotstxt(['debug' => false]);
        $this->assertInstanceOf(Robotstxt::class, $robotstxt);
    }

    public function testToArray()
    {
        $robotstxt = new Robotstxt(['debug' => false]);
        $this->assertIsArray($robotstxt->toArray());
    }

    public function testToTxt()
    {
        $robotstxt = new Robotstxt(['debug' => false]);
        $this->assertIsString($robotstxt->toTxt());
    }

    public function testDisallowAllOnDebug()
    {
        $robotstxt = new Robotstxt(['debug' => true]);
        $this->assertStringContainsString('disallow: /' . PHP_EOL, $robotstxt->toTxt());

        $robotstxt = new Robotstxt(['debug' => false]);
        $this->assertStringNotContainsString('disallow: /' . PHP_EOL, $robotstxt->toTxt());
    }

    public function testAddContent()
    {
        $robotstxt = new Robotstxt(['content' => '#Test Content']);
        $this->assertStringStartsWith('#Test Content' . PHP_EOL, $robotstxt->toTxt());

        $robotstxt = new Robotstxt(['content' => null]);
        $this->assertStringStartsWith('user-agent', $robotstxt->toTxt());

        $robotstxt = new Robotstxt(['content' => function () {
            return '# Callable';
        }]);
        $this->assertStringStartsWith('# Callable' . PHP_EOL, $robotstxt->toTxt());
    }

    public function testAddGroups()
    {
        $robotstxt = new Robotstxt(['debug' => false, 'groups' => null]);
        $this->assertNull($robotstxt->toTxt());

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
                            ]
                        ]
                    ];
                },
            ]
        );
        $txt = $robotstxt->toTxt();
        $this->assertRegExp('/user-agent: \*\ndisallow: \/kirby\/\ndisallow: \/site\/\nallow: \/media\//', $txt);

        $robotstxt = new Robotstxt(['debug' => false, 'groups' => "user-agent: *\ndisallow: /panel/\n"]);
        $this->assertRegExp('/user-agent: \*\ndisallow: \/panel\/\n/' . PHP_EOL, $robotstxt->toTxt());

        $robotstxt = new Robotstxt(['debug' => true, 'groups' => "user-agent: *\ndisallow: /panel/\n"]);
        $this->assertNotRegExp('/user-agent: \*\ndisallow: \/panel\/\n/' . PHP_EOL, $robotstxt->toTxt());
    }

    public function testAddSitemap()
    {
        $robotstxt = new Robotstxt(['sitemap' => '/sitemap.xml']);
        $this->assertRegExp('/sitemap: .*\/sitemap.xml/', $robotstxt->toTxt());

        $robotstxt = new Robotstxt(['sitemap' => function () {
            return '/sitemap.xml';
        }]);
        $this->assertRegExp('/sitemap: .*\/sitemap.xml/', $robotstxt->toTxt());
    }
}
