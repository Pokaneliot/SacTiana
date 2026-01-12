<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Create a new user with hashed password',
)]
class CreateUserCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('login', InputArgument::REQUIRED, 'User login')
            ->addArgument('password', InputArgument::REQUIRED, 'User password')
            ->addArgument('name', InputArgument::REQUIRED, 'User name')
            ->addOption('role', 'r', InputOption::VALUE_OPTIONAL, 'User role (ROLE_ADMIN or ROLE_USER)', 'ROLE_USER');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $login = $input->getArgument('login');
        $password = $input->getArgument('password');
        $name = $input->getArgument('name');
        $role = $input->getOption('role');

        // Validate role
        if (!in_array($role, ['ROLE_ADMIN', 'ROLE_USER'])) {
            $io->error('Role must be either ROLE_ADMIN or ROLE_USER');
            return Command::FAILURE;
        }

        // Check if user already exists
        $existingUser = $this->entityManager->getRepository(User::class)->findByLogin($login);
        if ($existingUser) {
            $io->error(sprintf('User with login "%s" already exists', $login));
            return Command::FAILURE;
        }

        // Create new user
        $user = new User();
        $user->setLogin($login);
        $user->setName($name);
        $user->setRole($role);
        $user->setCreatedAt(new \DateTime());

        // Hash password
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        // Save user
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success(sprintf(
            'User created successfully! Login: %s, Name: %s, Role: %s',
            $login,
            $name,
            $role
        ));

        return Command::SUCCESS;
    }
}
