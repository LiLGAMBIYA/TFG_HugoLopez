<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Ya existe una cuenta registrada con este correo electrónico.')]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, Vehiculo>
     */
    #[ORM\OneToMany(targetEntity: Vehiculo::class, mappedBy: 'propietario')]
    private Collection $vehiculos;

    /**
     * @var Collection<int, Cita>
     */
    #[ORM\OneToMany(targetEntity: Cita::class, mappedBy: 'cliente')]
    private Collection $citasComoCliente;

    /**
     * @var Collection<int, Cita>
     */
    #[ORM\OneToMany(targetEntity: Cita::class, mappedBy: 'operario')]
    private Collection $citasComoOperario;

    public function __construct()
    {
        $this->vehiculos = new ArrayCollection();
        $this->citasComoCliente = new ArrayCollection();
        $this->citasComoOperario = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, Vehiculo>
     */
    public function getVehiculos(): Collection
    {
        return $this->vehiculos;
    }

    public function addVehiculo(Vehiculo $vehiculo): static
    {
        if (!$this->vehiculos->contains($vehiculo)) {
            $this->vehiculos->add($vehiculo);
            $vehiculo->setPropietario($this);
        }

        return $this;
    }

    public function removeVehiculo(Vehiculo $vehiculo): static
    {
        if ($this->vehiculos->removeElement($vehiculo)) {
            // set the owning side to null (unless already changed)
            if ($vehiculo->getPropietario() === $this) {
                $vehiculo->setPropietario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Cita>
     */
    public function getCitasComoCliente(): Collection
    {
        return $this->citasComoCliente;
    }

    public function addCitasComoCliente(Cita $citasComoCliente): static
    {
        if (!$this->citasComoCliente->contains($citasComoCliente)) {
            $this->citasComoCliente->add($citasComoCliente);
            $citasComoCliente->setCliente($this);
        }

        return $this;
    }

    public function removeCitasComoCliente(Cita $citasComoCliente): static
    {
        if ($this->citasComoCliente->removeElement($citasComoCliente)) {
            // set the owning side to null (unless already changed)
            if ($citasComoCliente->getCliente() === $this) {
                $citasComoCliente->setCliente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Cita>
     */
    public function getCitasComoOperario(): Collection
    {
        return $this->citasComoOperario;
    }

    public function addCitasComoOperario(Cita $citasComoOperario): static
    {
        if (!$this->citasComoOperario->contains($citasComoOperario)) {
            $this->citasComoOperario->add($citasComoOperario);
            $citasComoOperario->setOperario($this);
        }

        return $this;
    }

    public function removeCitasComoOperario(Cita $citasComoOperario): static
    {
        if ($this->citasComoOperario->removeElement($citasComoOperario)) {
            // set the owning side to null (unless already changed)
            if ($citasComoOperario->getOperario() === $this) {
                $citasComoOperario->setOperario(null);
            }
        }

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }
}
