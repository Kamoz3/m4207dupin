<?php

namespace App\Entity;

use App\Repository\AccesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AccesRepository::class)
 */
class Acces
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="acces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateurId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateurId(): ?Utilisateur
    {
        return $this->utilisateurId;
    }

    public function setUtilisateurId(?Utilisateur $utilisateurId): self
    {
        $this->utilisateurId = $utilisateurId;

        return $this;
    }
}
