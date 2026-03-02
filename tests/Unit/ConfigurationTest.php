<?php

namespace Tests\Unit;

use Codeception\Attribute\Depends;
use Tests\Support\Data\Config\Generator;
use Tests\Support\UnitTester;
use Yii;
use yii\base\InvalidConfigException;

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
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessageMatches('/site key/');

        $config = Generator::getConfig();
        unset($config['siteKey']);

        Yii::createObject($config);
    }

    #[Depends('testFileExists')]
    public function testEmptySecretKey()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessageMatches('/secret key/');

        $config = Generator::getConfig();
        unset($config['secretKey']);

        Yii::createObject($config);
    }
}
