<?php


namespace Tests\Unit\Models;

use NsuSoft\Captcha\Models\Host;
use Tests\Support\UnitTester;

class HostTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    protected function _before()
    {
    }

    public function testGetBaseUriWithPort()
    {
        $host = new Host([
            'server' => 'http://localhost/',
            'port' => 3000,
        ]);

        $this->assertEquals('http://localhost:3000', $host->getBaseUri());
    }

    public function testGetBaseUriWithoutPort()
    {
        $host = new Host([
            'server' => 'http://localhost/',
        ]);

        $this->assertEquals('http://localhost', $host->getBaseUri());
    }
}
