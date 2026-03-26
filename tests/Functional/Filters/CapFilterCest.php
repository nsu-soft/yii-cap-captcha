<?php

declare(strict_types=1);

namespace Tests\Functional\Filters;

use Tests\Support\FunctionalTester;

final class CapFilterCest
{
    public function _before(FunctionalTester $I): void
    {
    }

    public function tryToTest(FunctionalTester $I): void
    {
        $I->amOnPage('index-test.php?r=test/index');
        $I->dontSee('index');
    }
}
