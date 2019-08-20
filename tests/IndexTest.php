<?php

use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    protected function setUp(): void
    {
        $this->setOutputCallback(function () {
        });
    }

    public function testIndex()
    {
        $response = kirby()->render('/robots.txt');
        $this->assertTrue($response->code() === 200);
    }
}
