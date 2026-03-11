<?php


namespace Tests\Unit\factories;

use NsuSoft\Captcha\Factories\PsrFactory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Tests\Support\UnitTester;

class PsrFactoryTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    public function testCreateFactory()
    {
        $factory = PsrFactory::createFactory();

        $this->assertInstanceOf(RequestFactoryInterface::class, $factory);
        $this->assertInstanceOf(ResponseFactoryInterface::class, $factory);
        $this->assertInstanceOf(ServerRequestFactoryInterface::class, $factory);
        $this->assertInstanceOf(StreamFactoryInterface::class, $factory);
        $this->assertInstanceOf(UploadedFileFactoryInterface::class, $factory);
        $this->assertInstanceOf(UriFactoryInterface::class, $factory);
    }

    public function testCreateClient()
    {
        $client = PsrFactory::createClient();

        $this->assertInstanceOf(ClientInterface::class, $client);
    }
}
