<?php

namespace App\Entity;

use App\Repository\CitaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CitaRepository::class)]
class Cita
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $clienteNombre = null;

    #[ORM\Column(length: 20)]
    private ?string $telefono = null;

    #[ORM\Column(length: 20)]
    private ?string $matricula = null;

    #[ORM\Column(length: 255)]
    private ?string $modeloCoche = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descripcionAveria = null;

    #[ORM\Column]
    private ?\DateTime $fechaDeseada = null;

    #[ORM\Column(length: 20)]
    private ?string $estado = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $fechaCreacion = null;

    #[ORM\ManyToOne(inversedBy: 'citas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Servicio $servicio = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClienteNombre(): ?string
    {
        return $this->clienteNombre;
    }

    public function setClienteNombre(string $clienteNombre): static
    {
        $this->clienteNombre = $clienteNombre;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(string $telefono): static
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getMatricula(): ?string
    {
        return $this->matricula;
    }

    public function setMatricula(string $matricula): static
    {
        $this->matricula = $matricula;

        return $this;
    }

    public function getModeloCoche(): ?string
    {
        return $this->modeloCoche;
    }

    public function setModeloCoche(string $modeloCoche): static
    {
        $this->modeloCoche = $modeloCoche;

        return $this;
    }

    public function getDescripcionAveria(): ?string
    {
        return $this->descripcionAveria;
    }

    public function setDescripcionAveria(string $descripcionAveria): static
    {
        $this->descripcionAveria = $descripcionAveria;

        return $this;
    }

    public function getFechaDeseada(): ?\DateTime
    {
        return $this->fechaDeseada;
    }

    public function setFechaDeseada(\DateTime $fechaDeseada): static
    {
        $this->fechaDeseada = $fechaDeseada;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): static
    {
        $this->estado = $estado;

        return $this;
    }

    public function getFechaCreacion(): ?\DateTimeImmutable
    {
        return $this->fechaCreacion;
    }

    public function setFechaCreacion(\DateTimeImmutable $fechaCreacion): static
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    public function getServicio(): ?Servicio
    {
        return $this->servicio;
    }

    public function setServicio(?Servicio $servicio): static
    {
        $this->servicio = $servicio;

        return $this;
    }
}
