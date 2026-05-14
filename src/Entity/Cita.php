<?php

namespace App\Entity;

use App\Repository\CitaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CitaRepository::class)]
class Cita
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descripcionAveria = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaDeseada = null;

    #[ORM\Column(length: 50)]
    private ?string $estado = 'Pendiente';

    #[ORM\Column]
    private ?\DateTimeImmutable $fechaCreacion = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $clienteNombre = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $telefono = null;


    #[ORM\ManyToOne(inversedBy: 'citas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Servicio $servicio = null;

    #[ORM\ManyToOne(inversedBy: 'citas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Vehiculo $vehiculo = null;

    #[ORM\ManyToOne(inversedBy: 'citasComoCliente')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Usuario $cliente = null;

    #[ORM\ManyToOne(inversedBy: 'citasComoOperario')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Usuario $operario = null;

    /**
     * @var Collection<int, CitaPieza>
     */
    #[ORM\OneToMany(targetEntity: CitaPieza::class, mappedBy: 'cita')]
    private Collection $citaPiezas;

    #[ORM\OneToOne(mappedBy: 'cita', cascade: ['persist', 'remove'])]
    private ?Factura $factura = null;

    public function __construct()
    {
        $this->citaPiezas = new ArrayCollection();
        $this->fechaCreacion = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFechaDeseada(): ?\DateTimeInterface
    {
        return $this->fechaDeseada;
    }

    public function setFechaDeseada(\DateTimeInterface $fechaDeseada): static
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

    public function getClienteNombre(): ?string
    {
        return $this->clienteNombre;
    }

    public function setClienteNombre(?string $clienteNombre): static
    {
        $this->clienteNombre = $clienteNombre;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): static
    {
        $this->telefono = $telefono;

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

    public function getVehiculo(): ?Vehiculo
    {
        return $this->vehiculo;
    }

    public function setVehiculo(?Vehiculo $vehiculo): static
    {
        $this->vehiculo = $vehiculo;

        return $this;
    }

    public function getCliente(): ?Usuario
    {
        return $this->cliente;
    }

    public function setCliente(?Usuario $cliente): static
    {
        $this->cliente = $cliente;

        return $this;
    }

    public function getOperario(): ?Usuario
    {
        return $this->operario;
    }

    public function setOperario(?Usuario $operario): static
    {
        $this->operario = $operario;

        return $this;
    }

    /**
     * @return Collection<int, CitaPieza>
     */
    public function getCitaPiezas(): Collection
    {
        return $this->citaPiezas;
    }

    public function addCitaPieza(CitaPieza $citaPieza): static
    {
        if (!$this->citaPiezas->contains($citaPieza)) {
            $this->citaPiezas->add($citaPieza);
            $citaPieza->setCita($this);
        }

        return $this;
    }

    public function removeCitaPieza(CitaPieza $citaPieza): static
    {
        if ($this->citaPiezas->removeElement($citaPieza)) {
            // set the owning side to null (unless already changed)
            if ($citaPieza->getCita() === $this) {
                $citaPieza->setCita(null);
            }
        }

        return $this;
    }

    public function getFactura(): ?Factura
    {
        return $this->factura;
    }

    public function setFactura(?Factura $factura): static
    {
        // unset the owning side of the relation if necessary
        if ($factura === null && $this->factura !== null) {
            $this->factura->setCita(null);
        }

        // set the owning side of the relation if necessary
        if ($factura !== null && $factura->getCita() !== $this) {
            $factura->setCita($this);
        }

        $this->factura = $factura;

        return $this;
    }
}
