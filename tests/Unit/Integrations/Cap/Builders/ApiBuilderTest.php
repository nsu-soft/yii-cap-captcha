<?php


namespace Tests\Unit\Integrations\Cap\Builders;

use NsuSoft\Captcha\Integrations\Cap\Builders\ApiBuilder;
use Tests\Support\Data\Config\Generator;
use Tests\Support\UnitTester;

class ApiBuilderTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    protected ?ApiBuilder $builder = null;

    protected function _before()
    {
        $config = Generator::getCaptchaCredentials();

        $this->builder = new ApiBuilder([
            'server' => $config['server'],
            'port' => $config['port'],
            'siteKey' => $config['siteKey'],
            'secretKey' => $config['secretKey'],
            'apiKey' => $config['apiKey'],
        ]);
    }

    public function testBuild()
    {
        $api = $this->builder->build();

        $this->assertIsObject($api->main);
        $this->assertTrue(method_exists($api->main, 'challenge'));

        $this->assertIsObject($api->server);
        $this->assertTrue(method_exists($api->server->main, 'about'));

        $this->assertIsObject($api->server->keys);
        $this->assertTrue(method_exists($api->server->keys->main, 'index'));

        $this->assertIsObject($api->server->settings);
        $this->assertTrue(method_exists($api->server->settings->main, 'sessions'));
        $this->assertTrue(method_exists($api->server->settings->apikeys, 'index'));
    }
}
