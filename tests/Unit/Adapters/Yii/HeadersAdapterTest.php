<?php


namespace Tests\Unit\Adapters\Yii;

use NsuSoft\Captcha\Adapters\Yii\HeadersAdapter;
use Nyholm\Psr7\Factory\Psr17Factory;
use Tests\Support\UnitTester;

class HeadersAdapterTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    protected Psr17Factory $factory;

    protected function _before()
    {
        $this->factory = new Psr17Factory();
    }

    public function testToYii()
    {
        $request = $this->factory->createRequest('GET', '/test')
            ->withHeader('Content-Type', 'text/html;charset=UTF-8')
            ->withHeader('Cache-Control', ['no-store', 'no-cache']);

        $this->assertEquals(
            [
                'Content-Type' => 'text/html;charset=UTF-8',
                'Cache-Control' => 'no-store, no-cache',
            ],
            HeadersAdapter::toYii($request)
        );
    }
}
