<?php

namespace Tests\Support\Data\Cap;

use JSONSchemaFaker\Faker;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use SplFileInfo;
use yii\helpers\Json;

class Schema
{
    /**
     * Gets schema file from current directory.
     * @param string $fileName
     * @return object
     */
    public static function getSchema(string $fileName): object
    {
        return (object)['$ref' => 'file://' . self::getFilePath($fileName)];
    }

    /**
     * Generates fake response with specified JSON schema.
     * @param string $fileName
     * @param ResponseFactoryInterface|StreamFactoryInterface $factory
     * @return ResponseInterface
     */
    public static function generateResponse(
        string $fileName,
        ResponseFactoryInterface|StreamFactoryInterface $factory
    ): ResponseInterface
    {
        $faker = new Faker();
        $fakeData = $faker->generate(new SplFileInfo(self::getFilePath($fileName)));
        $stream = $factory->createStream(Json::encode($fakeData));

        return $factory->createResponse()->withBody($stream);
    }

    /**
     * Gets absolute file path with JSON schema.
     * @param string $fileName
     * @return string
     */
    private static function getFilePath(string $fileName): string
    {
        return realpath(__DIR__ . $fileName . '.json');
    }
}