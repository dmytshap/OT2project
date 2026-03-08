<?php

declare(strict_types=1);

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

final class MainCest
{
    public function _before(AcceptanceTester $I): void
    {
        // Code here will be executed before each test function.
    }

    // All `public` methods will be executed as tests.
    public function tryToTest(AcceptanceTester $I): void
    {
        // Write your test content here.

        $I -> amOnPage('/frontend/uusi_main.php');
        //$I -> click(['class' => 'dropdown-item']);

        $I -> click(['class' => 'btn-otayhteytta']);

        //Muut linkit
        
        $I -> click('Projektitori');
        $I -> click('Hallinta');
    }
}
