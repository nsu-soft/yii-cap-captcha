<?php


namespace Tests\Unit\integrations\cap\api\server;

use Codeception\Stub;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use nsusoft\captcha\integrations\cap\api\server\Server;
use Tests\Support\Data\Cap\Schema;
use Tests\Support\Data\Config\Generator;
use Tests\Support\UnitTester;

class ServerTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    protected ?Server $api = null;

    protected function _before()
    {
        $config = Generator::getCaptchaCredentials();

        $this->api = new Server([
            'server' => $config['server'],
            'port' => $config['port'],
            'siteKey' => $config['siteKey'],
            'secretKey' => $config['secretKey'],
            'apiKey' => $config['apiKey'],
            'factory' => new HttpFactory(),
            'client' => new Client(),
        ]);
    }

    public function testAbout()
    {
        // construct
        $client = Stub::make(Client::class, [
            'sendRequest' => Schema::generateResponse('/server/about.200', new HttpFactory()),
        ]);

        $this->api->setClient($client);
        
        $response = $this->api->about();

        $this->assertIsObject($response);
        $this->tester->assertJsonSchema(Schema::getSchema('/server/about.200'), $response);
    }

    public function testLogout()
    {
        $this->markTestIncomplete();
    }
}
