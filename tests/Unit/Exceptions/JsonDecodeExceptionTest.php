<?php


namespace Tests\Unit\Exceptions;

use NsuSoft\Captcha\Exceptions\JsonDecodeException;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Tests\Support\UnitTester;
use yii\base\InvalidArgumentException;

class JsonDecodeExceptionTest extends \Codeception\Test\Unit
{
    const ERROR_MESSAGE = 'error-message';
    const ERROR_CODE = 1;

    protected UnitTester $tester;

    protected Psr17Factory $factory;

    protected function _before()
    {
        $this->factory = new Psr17Factory();
    }

    public function testConstruct()
    {
        $argumentException = new InvalidArgumentException(self::ERROR_MESSAGE, self::ERROR_CODE);
        $response = $this->factory->createResponse(200);

        $jsonException = new JsonDecodeException($argumentException, $response);

        $this->assertEquals(self::ERROR_MESSAGE, $jsonException->getMessage());
        $this->assertEquals(self::ERROR_CODE, $jsonException->getCode());
        
        $this->assertInstanceOf(ResponseInterface::class, $jsonException->getResponse());
        $this->assertEquals(200, $jsonException->getResponse()->getStatusCode());
    }
}
