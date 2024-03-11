<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Email;

class EmailValidationTest extends TestCase
{
    public function testValidEmailFormat()
    {
        $validator = Validation::createValidator();

        // Définir une adresse email valide
        $email = 'test@example.com';

        // Valider l'adresse email
        $violations = $validator->validate($email, [
            new Email()
        ]);

        // Vérifier qu'il n'y a pas de violations de contraintes
        $this->assertCount(0, $violations);
    }

    public function testInvalidEmailFormat()
    {
        $validator = Validation::createValidator();

        // Définir une adresse email invalide
        $email = 'adresse-email-invalid';

        // Valider l'adresse email
        $violations = $validator->validate($email, [
            new Email()
        ]);

        // Vérifier qu'il y a une violation de contrainte
        $this->assertCount(1, $violations);

        // Vérifier que le message d'erreur contient le mot "email"
        $this->assertStringContainsString('email', $violations[0]->getMessage());
    }
}
