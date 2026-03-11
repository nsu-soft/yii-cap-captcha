<?php


namespace Tests\Unit\adapters\yii;

use nsusoft\captcha\adapters\yii\RequestAdapter;
use Nyholm\Psr7\Factory\Psr17Factory;
use Tests\Support\UnitTester;
use yii\web\HeaderCollection;

class RequestAdapterTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    protected Psr17Factory $factory;

    protected function _before()
    {
        $this->factory = new Psr17Factory();
    }

    public function testToYii()
    {
        $stream = $this->factory->createStream('{"key": "value"}');

        $request = $this->factory->createRequest('GET', '/test')
            ->withHeader('Content-Type', 'text/html;charset=UTF-8')
            ->withHeader('Cache-Control', ['no-store', 'no-cache'])
            ->withBody($stream)
            ->withProtocolVersion('1.1');
        
        $adapter = new RequestAdapter();
        $yiiRequest = $adapter->toYii($request);

        $this->assertEquals('GET', $yiiRequest->getMethod());
        $this->assertEquals('/test', $yiiRequest->getUrl());
        $this->assertEquals('{"key": "value"}', $yiiRequest->getContent());
        $this->assertEquals('1.1', $yiiRequest->getOptions()['protocolVersion']);

        $headers = $yiiRequest->getHeaders();

        $this->assertInstanceOf(HeaderCollection::class, $headers);
        $this->assertEquals('text/html;charset=UTF-8', $headers->get('Content-Type'));
        $this->assertEquals('no-store, no-cache', $headers->get('Cache-Control'));
    }
}
