<?php


namespace Tests\Unit\Filters;

use NsuSoft\Captcha\Cap;
use NsuSoft\Captcha\Filters\CapFilter;
use Tests\Support\UnitTester;
use yii\web\Controller;
use yii\web\Request;

class CapFilterTest extends \Codeception\Test\Unit
{
    const ACTION = 'action';
    const TOKEN = 'token';

    protected UnitTester $tester;

    protected function _before()
    {
    }

    public function testSucceedValidation()
    {
        $component = $this->makeSucceedStub();

        $filter = new CapFilter([
            'cap' => $component,
        ]);

        $filter->owner = $this->makeControllerStub(self::TOKEN);

        $this->assertTrue($filter->beforeAction(self::ACTION));
    }

    public function testFailedValidation()
    {
        $component = $this->makeFailedStub();

        $filter = new CapFilter([
            'cap' => $component,
        ]);

        $filter->owner = $this->makeControllerStub(self::TOKEN);

        $this->assertFalse($filter->beforeAction(self::ACTION));
    }

    public function testEmptyToken()
    {
        $component = $this->makeSucceedStub();

        $filter = new CapFilter([
            'cap' => $component,
        ]);

        $filter->owner = $this->makeControllerStub(null);

        $this->assertFalse($filter->beforeAction(self::ACTION));
    }

    public function testClientSuppliedToken()
    {
        $component = $this->makeSucceedStub();

        $filter = new CapFilter([
            'cap' => $component,
            'clientSuppliedToken' => self::TOKEN,
        ]);

        $this->assertTrue($filter->beforeAction(self::ACTION));
    }

    private function makeSucceedStub()
    {
        return $this->make(Cap::class, [
            'siteVerify' => (object) ['success' => true],
        ]);
    }

    private function makeFailedStub()
    {
        return $this->make(Cap::class, [
            'siteVerify' => (object) ['success' => false],
        ]);
    }

    private function makeControllerStub(?string $token)
    {
        $request = $this->make(Request::class, [
            'getBodyParam' => $token,
        ]);

        return $this->make(Controller::class, [
            'request' => $request,
        ]);
    }
}
