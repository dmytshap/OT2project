<?php

declare(strict_types=1);

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

final class HallintaCest
{
    public function _before(AcceptanceTester $I): void
    {
        // Code here will be executed before each test function.
    }

    // All `public` methods will be executed as tests.
    public function tryToTest(AcceptanceTester $I): void
    {
        // Write your test content here.
        $I -> amOnPage('/frontend/hallinta.php');

        //TODO tämä loppuun
        // see ja dontSee testit painalluksien jälkeen
        // Jos Acceptance.suite.yml -tiedostossa PhpBrowser eikä WebDriver niin dontSeeElement ei toimi
        //$I -> dontSeeElement('#btn-julkaise'); 
        $I -> click(['class' => 'form-check-input']);
        $I -> seeElement('#btn-julkaise');
    }
}
