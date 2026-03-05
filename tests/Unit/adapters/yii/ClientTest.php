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

    public function testSendRequest()
    {
        $this->markTestIncomplete();
    }

    public function testСreateRequest()
    {
        $client = new Client();
        $request = $client->createRequest('GET', '/test');

        $this->assertInstanceOf(RequestInterface::class, $request);
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/test', $request->getUri());
    }

    public function testCreateResponse()
    {
        $this->markTestIncomplete();
    }

    public function testCreateServerRequest()
    {
        $this->markTestIncomplete();
    }

    public function testCreateStream()
    {
        $this->markTestIncomplete();
    }

    public function testCreateStreamFromFile()
    {
        $this->markTestIncomplete();
    }

    public function testCreateStreamFromResource()
    {
        $this->markTestIncomplete();
    }

    public function testCreateUploadedFile()
    {
        $this->markTestIncomplete();
    }

    public function testCreateUri()
    {
        $this->markTestIncomplete();
    }
}
