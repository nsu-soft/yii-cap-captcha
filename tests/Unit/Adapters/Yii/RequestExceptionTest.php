<?php


namespace Tests\Unit\Adapters\Yii;

use NsuSoft\Captcha\Adapters\Yii\RequestException;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\RequestInterface;
use Tests\Support\UnitTester;
use yii\httpclient\Exception;

class RequestExceptionTest extends \Codeception\Test\Unit
{
    const YII_ERROR_MESSAGE = 'yii-error-message';
    const YII_ERROR_CODE = 1;

    protected UnitTester $tester;

    protected Psr17Factory $factory;

    protected function _before()
    {
        $this->factory = new Psr17Factory();
    }

    public function testConstruct()
    {
        $yiiException = new Exception(self::YII_ERROR_MESSAGE, self::YII_ERROR_CODE);
        $request = $this->factory->createRequest('GET', '/incorrect-path');
        
        $requestException = new RequestException($yiiException, $request);

        $this->assertEquals(self::YII_ERROR_MESSAGE, $requestException->getMessage());
        $this->assertEquals(self::YII_ERROR_CODE, $requestException->getCode());
        
        $this->assertInstanceOf(RequestInterface::class, $requestException->getRequest());
        $this->assertEquals('GET', $requestException->getRequest()->getMethod());
        $this->assertEquals('/incorrect-path', $requestException->getRequest()->getUri());
    }
}
