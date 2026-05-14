<?php

namespace App\Command;

use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Creates a default admin user.',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = 'admin@taller.com';
        $user = $this->entityManager->getRepository(Usuario::class)->findOneBy(['email' => $email]);

        if (!$user) {
            $user = new Usuario();
            $user->setEmail($email);
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, 'admin123')
            );

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $output->writeln("Admin user created: admin@taller.com / admin123");
        } else {
            $user->setRoles(['ROLE_ADMIN']);
            $this->entityManager->flush();
            $output->writeln("Admin user already existed, roles updated.");
        }

        return Command::SUCCESS;
    }
}
