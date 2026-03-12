<?php


namespace Tests\Unit\Integrations\Cap\Api\Server;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use NsuSoft\Captcha\Integrations\Cap\Api\Server\Server;
use Tests\Support\Data\Cap\Schema;
use Tests\Support\Data\Config\Generator;
use Tests\Support\UnitTester;

class ServerTest extends \Codeception\Test\Unit
{
    const SESSION = 'session';

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
        $client = $this->make(Client::class, [
            'sendRequest' => Schema::generateResponse('/Server/about.200', new HttpFactory()),
        ]);

        $this->api->setClient($client);
        
        // test
        $response = $this->api->about();

        $this->assertIsObject($response);
        $this->tester->assertJsonSchema(Schema::getSchema('/Server/about.200'), $response);
    }

    public function testLogout()
    {
        // construct
        $client = $this->make(Client::class, [
            'sendRequest' => Schema::generateResponseEmpty(new HttpFactory()),
        ]);

        $this->api->setClient($client);
        
        // test
        $response = $this->api->logout(self::SESSION);

        $this->assertNull($response);
    }
}
