<?php

namespace App\Command;

use App\Entity\Personnel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin-user',
    description: 'Creates a default admin user.',
)]
class CreateAdminUserCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $login = 'jahjoo';
        $password = '2005';
        $nom = 'Admin';

        $existingUser = $this->entityManager->getRepository(Personnel::class)->findOneBy(['login' => $login]);

        if ($existingUser) {
            $io->warning(sprintf('User "%s" already exists.', $login));
            return Command::SUCCESS;
        }

        $user = new Personnel();
        $user->setLogin($login);
        $user->setNom($nom);
        $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']); // Add ROLE_ADMIN explicitly

        $hashedPassword = $this->userPasswordHasher->hashPassword(
            $user,
            $password
        );
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success(sprintf('Admin user "%s" created successfully!', $login));

        return Command::SUCCESS;
    }
}
