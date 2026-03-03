<?php

declare(strict_types=1);

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

final class LomakeCest
{
    public function _before(AcceptanceTester $I): void
    {
        // Code here will be executed before each test function.
    }

    // All `public` methods will be executed as tests.
    public function tryToTest(AcceptanceTester $I): void
    {
        // Write your test content here.
        $I -> amOnPage('/frontend/uusi_lomake.php');

        // Itse lomake
        // TODO sallittuja ja ei sallittuja juttuja kunhan sivussa on logiikka
        $I -> fillField('Projektin nimi', 'Uusi hieno upee projekti');
        $I -> fillField('Yrityksen nimi', 'UEF');
        $I -> fillField('Lyhyt kuvaus', 'Tehkää uusi projektitori');
        $I -> fillField('Pitkä kuvaus', 'Projektitoriin pitää firmojen saada jotain kivoja projekteja laitettua');
        $I -> fillField('Puhelinnumero', '0401234567');
        $I -> fillField('Aikataulu', '2026-04-28');
        $I -> fillField('Sähköposti', 'mina@email.com');
        $I -> click('Lähetä');

        //Muut linkit
        $I -> click('Projektitori');
        $I -> click('Hallinta');
        $I -> click(['class' => 'dropdown-item']);
    }
}
