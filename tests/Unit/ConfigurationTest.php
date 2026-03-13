<?php

namespace Tests\Unit;

use Tests\Support\Data\Config\Generator;
use Tests\Support\UnitTester;

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
}
