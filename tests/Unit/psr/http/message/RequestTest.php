<?php


namespace Tests\Unit\psr\http\message;

use nsusoft\captcha\psr\http\message\Request;
use Tests\Support\UnitTester;

class RequestTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    protected ?Request $request = null;

    protected function _before()
    {
        $this->request = new Request();
    }

    public function testGetProtocolVersion()
    {
        $this->assertEquals('1.1', $this->request->getProtocolVersion());
    }

    /**
     * @depends testGetProtocolVersion
     */
    public function testWithProtocolVersion()
    {
        $request = $this->request->withProtocolVersion('1.0');

        $this->assertEquals('1.1', $this->request->getProtocolVersion());
        $this->assertEquals('1.0', $request->getProtocolVersion());
    }

    public function testGetHeaders()
    {
        $this->assertEmpty($this->request->getHeaders());

        $request = $this->request->withHeader('Content-Type', 'text/html;charset=UTF-8');

        $this->assertEquals(['Content-Type' => ['text/html;charset=UTF-8']], $request->getHeaders());
    }

    /**
     * @depends testWithHeader
     */
    public function testHasHeader()
    {
        $this->assertTrue(
            $this->request
                ->withHeader('Content-Type', 'text/html;charset=UTF-8')
                ->hasHeader('content-type')
        );
    }

    /**
     * @depends testWithHeader
     */
    public function testGetHeader()
    {
        $request = $this->request->withHeader('Content-Type', 'text/html;charset=UTF-8');
        $this->assertEquals(['text/html;charset=UTF-8'], $request->getHeader('content-type'));
    }

    /**
     * @depends testWithHeader
     */
    public function testGetHeaderLine()
    {
        $request = $this->request->withHeader('Cache-Control', [
            'no-store',
            'no-cache',
            'must-revalidate',
        ]);

        $this->assertEquals('no-store,no-cache,must-revalidate', $request->getHeaderLine('cache-control'));
    }

    public function testWithHeader()
    {
        $request = $this->request->withHeader('Content-Type', 'text/html;charset=UTF-8');

        $this->assertEquals([], $this->request->getHeaders(), "Method withHeader() isn't immutable.");
        $this->assertEquals(['Content-Type' => ['text/html;charset=UTF-8']], $request->getHeaders());

        $request = $request->withHeader('content-type', 'application/json;charset=UTF-8');
        
        $this->assertEquals(['Content-Type' => ['application/json;charset=UTF-8']], $request->getHeaders());

        $request = $this->request->withHeader('Cache-Control', ['no-store', 'no-cache']);
        
        $this->assertEquals(['Cache-Control' => ['no-store', 'no-cache']], $request->getHeaders());

        $request = $this->request
            ->withHeader('Content-Type', 'text/html;charset=UTF-8')
            ->withHeader('Cache-Control', ['no-store', 'no-cache']);
        
        $this->assertEquals(
            [
                'Content-Type' => ['text/html;charset=UTF-8'],
                'Cache-Control' => ['no-store', 'no-cache']
            ],
            $request->getHeaders()
        );
    }
}
