<?php

use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    protected function setUp(): void
    {
        $this->setOutputCallback(function () {});
    }

    public function test_index()
    {
        $response = kirby()->render('/robots.txt');
        $this->assertTrue($response->code() === 200);
    }
}
