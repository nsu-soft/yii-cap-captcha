<?php


namespace Tests\Unit\integrations\cap\api;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use NsuSoft\Captcha\Integrations\Cap\Api\Main;
use Tests\Support\Data\Cap\Schema;
use Tests\Support\Data\Config\Generator;
use Tests\Support\UnitTester;

class MainTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    protected array $config = [];

    protected ?Main $api = null;

    protected function _before()
    {
        $this->config = Generator::getCaptchaCredentials();

        $this->api = new Main([
            'server' => $this->config['server'],
            'port' => $this->config['port'],
            'siteKey' => $this->config['siteKey'],
            'secretKey' => $this->config['secretKey'],
            'apiKey' => $this->config['apiKey'],
            'factory' => new HttpFactory(),
            'client' => new Client(),
        ]);
    }

    public function testChallenge()
    {
        // construct
        $client = $this->make(Client::class, [
            'sendRequest' => Schema::generateResponse('/Main/challenge.200', new HttpFactory()),
        ]);

        $this->api->setClient($client);

        // test
        $response = $this->api->challenge($this->config['siteKey']);

        $this->assertIsObject($response);
        $this->tester->assertJsonSchema(Schema::getSchema('/Main/challenge.200'), $response);
    }

    public function testRedeem()
    {
        // construct
        $client = $this->make(Client::class, [
            'sendRequest' => Schema::generateResponse('/Main/redeem.200', new HttpFactory()),
        ]);

        $this->api->setClient($client);

        // test
        $response = $this->api->redeem($this->config['siteKey'], [
            'token' => 'token',
            'solutions' => ['solution'],
        ]);

        $this->assertIsObject($response);
        $this->tester->assertJsonSchema(Schema::getSchema('/Main/redeem.200'), $response);
    }

    public function testRedeemForbidden()
    {
        // construct
        $client = $this->make(Client::class, [
            'sendRequest' => Schema::generateResponse('/Main/redeem.403', new HttpFactory()),
        ]);

        $this->api->setClient($client);

        // test
        $response = $this->api->redeem($this->config['siteKey'], [
            'token' => 'invalid-token',
            'solutions' => ['invalid-solution'],
        ]);

        $this->assertIsObject($response);
        $this->tester->assertJsonSchema(Schema::getSchema('/Main/redeem.403'), $response);
    }

    public function testSiteverify()
    {
        // construct
        $client = $this->make(Client::class, [
            'sendRequest' => Schema::generateResponse('/Main/siteverify.200', new HttpFactory()),
        ]);

        $this->api->setClient($client);

        // test
        $response = $this->api->siteverify($this->config['siteKey'], [
            'secret' => $this->config['secretKey'],
            'response' => 'token',
        ]);

        $this->assertIsObject($response);
        $this->tester->assertJsonSchema(Schema::getSchema('/Main/siteverify.200'), $response);
    }

    public function testSiteverifyNotFound()
    {
        // construct
        $client = $this->make(Client::class, [
            'sendRequest' => Schema::generateResponse('/Main/siteverify.404', new HttpFactory()),
        ]);

        $this->api->setClient($client);

        // test
        $response = $this->api->siteverify($this->config['siteKey'], [
            'secret' => $this->config['secretKey'],
            'response' => 'invalid-token',
        ]);

        $this->assertIsObject($response);
        $this->tester->assertJsonSchema(Schema::getSchema('/Main/siteverify.404'), $response);
    }
}
