<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoteRepository::class)]
class Note
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $valeur = null;

    #[ORM\Column]
    private ?\DateTime $date_create = null;

    #[ORM\ManyToOne(inversedBy: 'notes')]
    private ?User $member = null;

    #[ORM\ManyToOne(inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Seance $seance = null;

    // Remplacement du lien User par une simple chaîne de caractères pour le nom du coach
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coachName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValeur(): ?int
    {
        return $this->valeur;
    }

    public function setValeur(int $valeur): static
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getDateCreate(): ?\DateTime
    {
        return $this->date_create;
    }

    public function setDateCreate(\DateTime $date_create): static
    {
        $this->date_create = $date_create;

        return $this;
    }

    public function getMember(): ?User
    {
        return $this->member;
    }

    public function setMember(?User $member): static
    {
        $this->member = $member;

        return $this;
    }

    public function getSeance(): ?Seance
    {
        return $this->seance;
    }

    public function setSeance(?Seance $seance): static
    {
        $this->seance = $seance;

        return $this;
    }

    public function getCoachName(): ?string
    {
        return $this->coachName;
    }

    public function setCoachName(?string $coachName): static
    {
        $this->coachName = $coachName;

        return $this;
    }
}