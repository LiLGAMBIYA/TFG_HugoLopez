<?php

namespace App\Command;

use App\Entity\Cita;
use App\Entity\Pieza;
use App\Entity\Servicio;
use App\Entity\Usuario;
use App\Entity\Vehiculo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:load-demo-data', description: 'Loads idempotent demo data for the JM Motor final delivery.')]
class LoadDemoDataCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $admin = $this->findOrCreateUser('admin@taller.com', ['ROLE_ADMIN'], 'admin123');
        $client = $this->findOrCreateUser('cliente@taller.com', ['ROLE_USER'], 'cliente123');

        $revision = $this->findOrCreateServicio('Revisión general', 'Diagnóstico completo del estado del vehículo y revisión de seguridad.', 50.0);
        $this->findOrCreateServicio('Cambio de aceite', 'Sustitución de aceite y filtro según especificaciones del fabricante.', 90.0);
        $this->findOrCreateServicio('Cambio de neumáticos', 'Montaje, equilibrado y revisión de presión de neumáticos.', 50.0);
        $this->findOrCreatePieza('FLT-AUDI-001', 'Filtro de aceite Audi/VW', 14.95, 12);
        $this->findOrCreatePieza('ACE-5W30-001', 'Aceite sintético 5W30', 38.50, 20);

        $vehiculo = $this->entityManager->getRepository(Vehiculo::class)->findOneBy(['matricula' => '1234ABC']);
        if (!$vehiculo) {
            $vehiculo = new Vehiculo();
            $vehiculo->setMatricula('1234ABC');
            $vehiculo->setMarca('Volkswagen');
            $vehiculo->setModelo('Golf');
            $vehiculo->setVin('WVWZZZ1KZAW000001');
            $vehiculo->setPropietario($client);
            $this->entityManager->persist($vehiculo);
        }

        if (!$this->entityManager->getRepository(Cita::class)->findOneBy(['cliente' => $client, 'vehiculo' => $vehiculo])) {
            $cita = new Cita();
            $cita->setCliente($client);
            $cita->setVehiculo($vehiculo);
            $cita->setServicio($revision);
            $cita->setClienteNombre('Cliente Demo');
            $cita->setTelefono('600000000');
            $cita->setDescripcionAveria('Ruido al frenar y revisión general antes de ITV.');
            $cita->setFechaDeseada(new \DateTimeImmutable('+2 days 10:00'));
            $cita->setEstado('Pendiente');
            $cita->setOperario($admin);
            $this->entityManager->persist($cita);
        }

        $this->entityManager->flush();
        $output->writeln('Datos de prueba cargados correctamente.');

        return Command::SUCCESS;
    }

    private function findOrCreateUser(string $email, array $roles, string $plainPassword): Usuario
    {
        $user = $this->entityManager->getRepository(Usuario::class)->findOneBy(['email' => $email]);
        if ($user) {
            return $user;
        }

        $user = new Usuario();
        $user->setEmail($email);
        $user->setRoles($roles);
        $user->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));
        $this->entityManager->persist($user);

        return $user;
    }

    private function findOrCreateServicio(string $nombre, string $descripcion, float $precio): Servicio
    {
        $servicio = $this->entityManager->getRepository(Servicio::class)->findOneBy(['nombre' => $nombre]);
        if ($servicio) {
            return $servicio;
        }

        $servicio = new Servicio();
        $servicio->setNombre($nombre);
        $servicio->setDescripcion($descripcion);
        $servicio->setPrecio($precio);
        $this->entityManager->persist($servicio);

        return $servicio;
    }

    private function findOrCreatePieza(string $referencia, string $nombre, float $precio, int $stock): Pieza
    {
        $pieza = $this->entityManager->getRepository(Pieza::class)->findOneBy(['referencia' => $referencia]);
        if ($pieza) {
            return $pieza;
        }

        $pieza = new Pieza();
        $pieza->setReferencia($referencia);
        $pieza->setNombre($nombre);
        $pieza->setPrecioUnidad($precio);
        $pieza->setStock($stock);
        $this->entityManager->persist($pieza);

        return $pieza;
    }
}
