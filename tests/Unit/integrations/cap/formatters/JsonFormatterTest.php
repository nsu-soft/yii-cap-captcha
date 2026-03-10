<?php


namespace Tests\Unit\integrations\cap\formatters;

use nsusoft\captcha\integrations\cap\formatters\JsonFormatter;
use Nyholm\Psr7\Factory\Psr17Factory;
use Tests\Support\UnitTester;
use yii\base\InvalidArgumentException;

class JsonFormatterTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    protected Psr17Factory $factory;

    protected function _before()
    {
        $this->factory = new Psr17Factory();
    }

    public function testFromResponse()
    {
        $stream = $this->factory->createStream('{"key": "value"}');

        $response = $this->factory->createResponse()
            ->withBody($stream);

        $json = JsonFormatter::fromResponse($response);

        $this->assertEquals('value', $json->key);
    }

    public function testFromResponseEmpty()
    {
        $stream = $this->factory->createStream('');

        $response = $this->factory->createResponse()
            ->withBody($stream);

        $json = JsonFormatter::fromResponse($response);

        $this->assertNull($json);
    }

    public function testFromResponseError()
    {
        $stream = $this->factory->createStream('"key": "value"');

        $response = $this->factory->createResponse()
            ->withBody($stream);

        $this->expectException(InvalidArgumentException::class);

        JsonFormatter::fromResponse($response);
    }
}
