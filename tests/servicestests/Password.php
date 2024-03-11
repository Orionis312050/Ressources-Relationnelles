<?php
namespace Tests;

use PHPUnit\Framework\TestCase;

class Password extends TestCase
{
    public function isPasswordValid(string $password): bool
    {
        $majusculeRegex = '/[A-Z]/';
        $chiffreRegex = '/[0-9]/';
        $specialRegex = '/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/';
        $minusculeRegex = '/[a-z]/';

        $longueurMinimale = strlen($password) >= 10;

        $contientMajuscule = preg_match($majusculeRegex, $password);
        $contientChiffre = preg_match($chiffreRegex, $password);
        $contientSpecial = preg_match($specialRegex, $password);
        $contientMinuscule = preg_match($minusculeRegex, $password);

        $motDePasseValide = $longueurMinimale && $contientMajuscule && $contientChiffre && $contientSpecial && $contientMinuscule;

        return $motDePasseValide;
    }
    
    public function testIsPasswordValid()
    {

        $this->assertTrue($this->isPasswordValid('MotDePasse1!'));

        // Test avec un mot de passe invalide (manque de caractères spéciaux)
        $this->assertFalse($this->isPasswordValid('MotDePasse1'));

        // Test avec un mot de passe invalide (pas de chiffres)
        $this->assertFalse($this->isPasswordValid('MotDePasse!'));

        // Test avec un mot de passe invalide (pas de majuscules)
        $this->assertFalse($this->isPasswordValid('motdepasse1!'));

        // Test avec un mot de passe invalide (trop court)
        $this->assertFalse($this->isPasswordValid('MdP1!'));
    }
}

?>