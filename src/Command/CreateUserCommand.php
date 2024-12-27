<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserCommand extends Command
{
    protected static $defaultDescription = 'Creates a new user';

    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct('app:create-user');
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('login', InputArgument::REQUIRED, 'User login')
            ->addArgument('phone', InputArgument::REQUIRED, 'User phone number')
            ->addArgument('password', InputArgument::REQUIRED, 'User password')
            ->addOption('admin', null, InputOption::VALUE_NONE, 'Assign ROLE_ADMIN to the user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $login = $input->getArgument('login');
        $phone = $input->getArgument('phone');
        $password = $input->getArgument('password');
        $isAdmin = $input->getOption('admin');

        $existingUser = $this->userRepository->findOneBy(['login' => $login]);
        if ($existingUser) {
            $io->error(sprintf('User with login "%s" already exists.', $login));
            return Command::FAILURE;
        }

        $user = new User();
        $user->setLogin($login);
        $user->setPhone($phone);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPass($hashedPassword);


        if ($isAdmin) {
            $user->setRoles(['ROLE_ADMIN']);
        } else {
            $user->setRoles(['ROLE_USER']);
        }

        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            $io->error('An error occurred while creating the user: ' . $e->getMessage());
            return Command::FAILURE;
        }

        $io->success(sprintf('User "%s" has been created successfully.', $login));

        return Command::SUCCESS;
    }
}
