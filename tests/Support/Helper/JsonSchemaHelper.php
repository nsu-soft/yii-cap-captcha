<?php

declare(strict_types=1);

namespace Tests\Support\Helper;

use JsonSchema\Validator;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class JsonSchemaHelper extends \Codeception\Module
{
    /**
     * Checks JSON schema.
     * @param object|array $schema
     * @param mixed $json
     */
    public function assertJsonSchema(object $schema, object|array $json, string $message = '')
    {
        $validator = new Validator();
        $validator->validate($json, $schema);

        $this->assertTrue(
            $validator->isValid(),
            empty($message) ? $this->getValidatorError($validator) : $message
        );
    }

    protected function getValidatorError(Validator $validator): string
    {
        return 'Incorrect JSON schema...' . PHP_EOL . print_r($validator->getErrors(), true);
    }
}
