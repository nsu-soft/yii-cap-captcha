<?php


namespace Tests\Unit\integrations\cap\api;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use nsusoft\captcha\integrations\cap\api\Main;
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
        $response = $this->api->challenge($this->config['siteKey']);

        $this->assertIsObject($response);
        $this->tester->assertJsonSchema(Schema::getSchema('/main/challenge.200'), $response);
    }

    public function testRedeem()
    {
        $this->markTestIncomplete();
    }

    public function testRedeemForbidden()
    {
        $challenge = $this->api->challenge($this->config['siteKey']);

        $response = $this->api->redeem($this->config['siteKey'], [
            'token' => $challenge->token,
            'solutions' => ['invalid-solution'],
        ]);

        $this->assertIsObject($response);
        $this->tester->assertJsonSchema(Schema::getSchema('/main/redeem.403'), $response);
    }

    public function testSiteverify()
    {
        $this->markTestIncomplete();
    }

    public function testSiteverifyNotFound()
    {
        $response = $this->api->siteverify($this->config['siteKey'], [
            'secret' => $this->config['secretKey'],
            'response' => 'invalid-token',
        ]);

        $this->assertIsObject($response);
        $this->tester->assertJsonSchema(Schema::getSchema('/main/siteverify.404'), $response);
    }
}
