<?php

namespace App\Entity;

use App\Repository\FacturaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FacturaRepository::class)]
class Factura
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20, unique: true)]
    private ?string $numeroFactura = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaEmision = null;

    #[ORM\Column]
    private ?float $baseImponible = null;

    #[ORM\Column]
    private ?float $iva = null;

    #[ORM\Column]
    private ?float $total = null;

    #[ORM\OneToOne(inversedBy: 'factura', cascade: ['persist', 'remove'])]
    private ?Cita $cita = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroFactura(): ?string
    {
        return $this->numeroFactura;
    }

    public function setNumeroFactura(string $numeroFactura): static
    {
        $this->numeroFactura = $numeroFactura;

        return $this;
    }

    public function getFechaEmision(): ?\DateTimeInterface
    {
        return $this->fechaEmision;
    }

    public function setFechaEmision(\DateTimeInterface $fechaEmision): static
    {
        $this->fechaEmision = $fechaEmision;

        return $this;
    }

    public function getBaseImponible(): ?float
    {
        return $this->baseImponible;
    }

    public function setBaseImponible(float $baseImponible): static
    {
        $this->baseImponible = $baseImponible;

        return $this;
    }

    public function getIva(): ?float
    {
        return $this->iva;
    }

    public function setIva(float $iva): static
    {
        $this->iva = $iva;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

        return $this;
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
}
