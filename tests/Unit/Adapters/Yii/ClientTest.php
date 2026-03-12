<?php


namespace Tests\Unit\Adapters\Yii;

use NsuSoft\Captcha\Adapters\Yii\Client;
use NsuSoft\Captcha\Adapters\Yii\RequestAdapter;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Client\ClientExceptionInterface;
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

    public function testSendRequestException()
    {
        $client = new Client();
        $request = $this->factory->createRequest('GET', '/incorrect-path');
        
        $this->expectException(ClientExceptionInterface::class);

        $client->sendRequest($request);
    }
}
