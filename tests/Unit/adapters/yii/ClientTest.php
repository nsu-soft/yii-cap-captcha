<?php


namespace Tests\Unit\adapters\yii;

use nsusoft\captcha\adapters\yii\Client;
use Psr\Http\Message\RequestInterface;
use Tests\Support\UnitTester;

class ClientTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    public function testСreateRequest()
    {
        $client = new Client();
        $request = $client->createRequest('GET', '/test');

        $this->assertInstanceOf(RequestInterface::class, $request);
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/test', $request->getUri());
    }
}
