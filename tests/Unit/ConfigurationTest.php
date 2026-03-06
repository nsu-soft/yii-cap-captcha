<?php

namespace Tests\Unit;

use Codeception\Attribute\Depends;
use Error;
use Tests\Support\Data\Config\Generator;
use Tests\Support\UnitTester;
use Yii;

class ConfigurationTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    protected function _before()
    {
    }

    public function testFileExists()
    {
        $captchaFile = Generator::getCaptchaCredentialsFile();
        $this->assertFileExists($captchaFile, "Captcha credentials file doesn't exist.");
    }

    #[Depends('testFileExists')]
    public function testEmptySiteKey()
    {
        $this->expectException(Error::class);
        $this->expectExceptionMessageMatches('/must not be accessed before initialization/');

        $config = Generator::getConfig();
        unset($config['siteKey']);

        Yii::createObject($config);
    }

    #[Depends('testFileExists')]
    public function testEmptySecretKey()
    {
        $this->expectException(Error::class);
        $this->expectExceptionMessageMatches('/must not be accessed before initialization/');

        $config = Generator::getConfig();
        unset($config['secretKey']);

        Yii::createObject($config);
    }
}
