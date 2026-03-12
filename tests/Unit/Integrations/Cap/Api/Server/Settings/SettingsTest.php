<?php


namespace Tests\Unit\Integrations\Cap\Api\Server\Settings;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use NsuSoft\Captcha\Integrations\Cap\Api\Server\Settings\Settings;
use Tests\Support\Data\Cap\Schema;
use Tests\Support\Data\Config\Generator;
use Tests\Support\UnitTester;

class SettingsTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    protected ?Settings $api = null;

    protected function _before()
    {
        $config = Generator::getCaptchaCredentials();

        $this->api = new Settings([
            'server' => $config['server'],
            'port' => $config['port'],
            'siteKey' => $config['siteKey'],
            'secretKey' => $config['secretKey'],
            'apiKey' => $config['apiKey'],
            'factory' => new HttpFactory(),
            'client' => new Client(),
        ]);
    }

    public function testSessions()
    {
        // construct
        $client = $this->make(Client::class, [
            'sendRequest' => Schema::generateResponse('/Server/Settings/sessions.200', new HttpFactory()),
        ]);

        $this->api->setClient($client);
    
        // test
        $sessions = $this->api->sessions();

        $this->assertIsArray($sessions);
        $this->tester->assertJsonSchema(Schema::getSchema('/Server/Settings/sessions.200'), $sessions);
    }
}
