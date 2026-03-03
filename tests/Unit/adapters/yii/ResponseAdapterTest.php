<?php


namespace Tests\Unit\adapters\yii;

use Nyholm\Psr7\Factory\Psr17Factory;
use Tests\Support\UnitTester;

class ResponseAdapterTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    protected Psr17Factory $factory;

    protected function _before()
    {
        $this->factory = new Psr17Factory();
    }

    public function testToPsr()
    {
        $this->markTestIncomplete();
    }
}
