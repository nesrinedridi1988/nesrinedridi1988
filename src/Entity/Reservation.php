<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $DateRes;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Statut;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRes(): ?DateTimeInterface
    {
        return $this->DateRes;
    }

    public function setDateRes(DateTimeInterface $DateRes): self
    {
        $this->DateRes = $DateRes;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->Statut;
    }

    public function setStatut(string $Statut): self
    {
        $this->Statut = $Statut;

        return $this;
    }
}
