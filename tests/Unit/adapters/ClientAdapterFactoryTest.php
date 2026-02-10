<?php


namespace Tests\Unit\adapters;

use nsusoft\captcha\adapters\ClientAdapterFactory;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\HttpClient\HttpClient as SymfonyNativeClient;
use Tests\Support\Data\Config\Generator;
use Tests\Support\UnitTester;
use Yii;
use yii\base\InvalidConfigException;

class ClientAdapterFactoryTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    public function testYiiClientAdapter()
    {
        $config = Generator::getYiiClientConfig();
        $client = Yii::createObject($config['client']);
        $client = ClientAdapterFactory::wrap($client);

        $this->assertInstanceOf(ClientInterface::class, $client);
    }

    public function testUnknownClientAdapter()
    {
        $client = Yii::createObject(function () {
            return SymfonyNativeClient::create();
        });

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessageMatches('/Unknown/');

        ClientAdapterFactory::wrap($client);
    }
}
