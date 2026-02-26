<?php

declare(strict_types=1);

namespace Tests\Support;

use JsonSchema\Validator;

/**
 * Inherited Methods
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
*/
class CapTester extends \Codeception\Actor
{
    use _generated\CapTesterActions;

    /**
     * Define custom actions here
     */

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
