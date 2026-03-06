<?php


namespace Tests\Unit;

use nsusoft\captcha\Cap;
use Tests\Support\Data\Config\Generator;
use Tests\Support\UnitTester;
use Yii;

class CapTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    protected ?Cap $component = null;

    protected function _before()
    {
        $config = Generator::getConfig();
        $this->component = Yii::createObject($config);
    }

    public function testChallenge()
    {
        $this->markTestIncomplete();
    }

    public function testRedeem()
    {
        $this->markTestIncomplete();
    }

    public function testSiteVerify()
    {
        $this->markTestIncomplete();
    }

    public function testGetAbout()
    {
        $this->markTestIncomplete();
    }

    public function testLogout()
    {
        $this->markTestIncomplete();
    }

    public function testGetKeys()
    {
        $this->markTestIncomplete();
    }

    public function testCreateKey()
    {
        $this->markTestIncomplete();
    }

    public function testViewKey()
    {
        $this->markTestIncomplete();
    }

    public function testDeleteKey()
    {
        $this->markTestIncomplete();
    }

    public function testConfigKey()
    {
        $this->markTestIncomplete();
    }

    public function testRotateSecret()
    {
        $this->markTestIncomplete();
    }

    public function testGetApiKeys()
    {
        $this->markTestIncomplete();
    }

    public function testCreateApiKey()
    {
        $this->markTestIncomplete();
    }

    public function testDeleteApiKey()
    {
        $this->markTestIncomplete();
    }

    public function testDeleteLastApiKey()
    {
        $this->markTestIncomplete();
    }

    public function testGetSessions()
    {
        $this->markTestIncomplete();
    }
}
