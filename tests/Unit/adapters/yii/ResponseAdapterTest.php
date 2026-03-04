<?php


namespace Tests\Unit\adapters\yii;

use nsusoft\captcha\adapters\yii\ResponseAdapter;
use Nyholm\Psr7\Factory\Psr17Factory;
use Tests\Support\UnitTester;
use yii\httpclient\Client;

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
        $client = new Client();

        $yiiResponse = $client->createResponse('{"key": "value"}', [
            'http-code' => 200,
            'Content-Type' => 'text/html;charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache',
        ]);

        $response = ResponseAdapter::toPsr($yiiResponse);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"key": "value"}', (string)$response->getBody());
        $this->assertEquals(['text/html;charset=UTF-8'], $response->getHeader('Content-Type'));
        $this->assertEquals(['no-store, no-cache'], $response->getHeader('Cache-Control'));
        $this->assertEquals([], $response->getHeader('http-Code'));
    }
}
