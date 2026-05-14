<?php

namespace App\Entity;

use App\Repository\PiezaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PiezaRepository::class)]
class Pieza
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $referencia = null;

    #[ORM\Column(length: 100)]
    private ?string $nombre = null;

    #[ORM\Column]
    private ?float $precioUnidad = null;

    #[ORM\Column]
    private ?int $stock = null;

    /**
     * @var Collection<int, CitaPieza>
     */
    #[ORM\OneToMany(targetEntity: CitaPieza::class, mappedBy: 'pieza')]
    private Collection $citaPiezas;

    public function __construct()
    {
        $this->citaPiezas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReferencia(): ?string
    {
        return $this->referencia;
    }

    public function setReferencia(string $referencia): static
    {
        $this->referencia = $referencia;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getPrecioUnidad(): ?float
    {
        return $this->precioUnidad;
    }

    public function setPrecioUnidad(float $precioUnidad): static
    {
        $this->precioUnidad = $precioUnidad;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

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
            $citaPieza->setPieza($this);
        }

        return $this;
    }

    public function removeCitaPieza(CitaPieza $citaPieza): static
    {
        if ($this->citaPiezas->removeElement($citaPieza)) {
            // set the owning side to null (unless already changed)
            if ($citaPieza->getPieza() === $this) {
                $citaPieza->setPieza(null);
            }
        }

        return $this;
    }
}
