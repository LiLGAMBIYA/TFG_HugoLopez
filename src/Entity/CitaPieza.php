<?php

namespace App\Entity;

use App\Repository\CitaPiezaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CitaPiezaRepository::class)]
class CitaPieza
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'citaPiezas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cita $cita = null;

    #[ORM\ManyToOne(inversedBy: 'citaPiezas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pieza $pieza = null;

    #[ORM\Column(options: ['default' => 1])]
    private ?int $cantidadUsada = 1;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCita(): ?Cita
    {
        return $this->cita;
    }

    public function setCita(?Cita $cita): static
    {
        $this->cita = $cita;

        return $this;
    }

    public function getPieza(): ?Pieza
    {
        return $this->pieza;
    }

    public function setPieza(?Pieza $pieza): static
    {
        $this->pieza = $pieza;

        return $this;
    }

    public function getCantidadUsada(): ?int
    {
        return $this->cantidadUsada;
    }

    public function setCantidadUsada(int $cantidadUsada): static
    {
        $this->cantidadUsada = $cantidadUsada;

        return $this;
    }
}
