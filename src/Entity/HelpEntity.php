<?php

namespace App\Entity;

use App\Repository\HelpEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HelpEntityRepository::class)]
class HelpEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Questions = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Answer = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private int $Status = 0;

    #[ORM\Column(length: 50)]
    private ?string $Catégorie = null;

    #[ORM\Column(length: 100)]
    private ?string $Email = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestions(): ?string
    {
        return $this->Questions;
    }

    public function setQuestions(string $Questions): static
    {
        $this->Questions = $Questions;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->Answer;
    }

    public function setAnswer(string $Answer): static
    {
        $this->Answer = $Answer;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->Status;
    }

    public function setStatus(int $Status): static
    {
        $this->Status = $Status;

        return $this;
    }

    public function getCatégorie(): ?string
    {
        return $this->Catégorie;
    }

    public function setCatégorie(string $Catégorie): static
    {
        $this->Catégorie = $Catégorie;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): static
    {
        $this->Email = $Email;

        return $this;
    }
}
