<?php

namespace NsuSoft\Captcha\Filters;

use NsuSoft\Captcha\Cap;
use Yii;
use yii\base\ActionFilter;

class CapFilter extends ActionFilter
{
    /**
     * @var string|null The user-provided captcha token to be validated. If `null`, the token 
     * will be retrieved from the [[hiddenFieldName]] POST field.
     */
    public ?string $clientSuppliedToken = null;

    /**
     * @var string Cap component name.
     */
    public string $componentName = 'captcha';

    /**
     * @var string Cap Captcha hidden field name, where cap token was saved,
     * when captcha was solved.
     */
    public string $hiddenFieldName = 'cap-token';

    /**
     * @var Cap Cap component instance.
     */
    protected Cap $cap;

    /**
     * @inheritDoc
     */
    public function init(): void
    {
        $this->cap = Yii::$app->get($this->componentName);
    }

    /**
     * Sets Cap component instance.
     * @param Cap $cap
     * @return void
     */
    public function setCap(Cap $cap): void
    {
        $this->cap = $cap;
    }

    /**
     * @inheritDoc
     */
    public function beforeAction($action): bool
    {
        $token = $this->getToken();

        if (is_null($token)) {
            return false;
        }
        
        return $this->validateToken($token);
    }

    /**
     * Gets token from a request object.
     * @return string|null `null`, if token wasn't received.
     */
    private function getToken(): ?string
    {
        if (isset($this->clientSuppliedToken)) {
            return $this->clientSuppliedToken;
        }

        return $this->owner->request->getBodyParam($this->hiddenFieldName);
    }

    /**
     * Checks if token is valid.
     * @return bool
     */
    private function validateToken(string $token): bool
    {
        return $this->cap->siteVerify($token)->success;
    }
}