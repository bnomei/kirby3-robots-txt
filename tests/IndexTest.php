<?php

use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{

    protected function setUp(): void
    {
        $this->setOutputCallback(function () { });
    }

    public function testIndex()
    {
        $r = kirby()->render('/robots.txt');
        $this->assertIsInt($r->code(), 200);
    }
}
