<?php

namespace Tests\Support\Data\Cap;

class Schema
{
    /**
     * Gets schema file from current directory.
     * @param string $fileName
     * @return object
     */
    public static function getSchema(string $fileName): object
    {
        return (object)['$ref' => 'file://' . realpath(__DIR__ . $fileName . '.json')];
    }
}