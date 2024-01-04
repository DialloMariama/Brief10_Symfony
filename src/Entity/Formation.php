<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\FormationRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
#[ApiResource]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["formation"])]
    #[ApiProperty(identifier: true)]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["formation"])]

    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Groups(["formation"])]

    private ?string $description = null;
     #[ORM\Column(length: 12)]
    // private ?string $etat = null;

    // #[ORM\Column(length: 20, options: ["default" => "en_cours"])]
    #[Groups(["formation"])]

    private ?string $etat = 'en_cours';

    #[ORM\Column(length: 255)]
    #[Groups(["formation"])]

    private ?string $duree = null;

    // Les méthodes setId et getId ne sont généralement pas nécessaires.
    public function getID(): ?string
    {
        return $this->id;
    }

    public function setID(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): static
    {
        $this->duree = $duree;

        return $this;
    }
}
