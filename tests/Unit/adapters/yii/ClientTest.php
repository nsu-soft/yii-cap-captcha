<?php


namespace Tests\Unit\adapters\yii;

use nsusoft\captcha\adapters\yii\Client;
use nsusoft\captcha\adapters\yii\RequestAdapter;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Tests\Support\UnitTester;
use yii\httpclient\Client as YiiClient;
use yii\httpclient\Request;

class ClientTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    protected Psr17Factory $factory;

    protected function _before()
    {
        $this->factory = new Psr17Factory();
    }

    public function testSendRequest()
    {
        $requestAdapter = $this->make(RequestAdapter::class, [
            'toYii' => $this->make(Request::class, [
                'send' => (new YiiClient())->createResponse('', ['http-code' => 200]),
            ]),
        ]);
        
        $client = new Client();
        $client->setRequestAdapter($requestAdapter);

        $request = $this->factory->createRequest('GET', '/test');
        $response = $client->sendRequest($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
