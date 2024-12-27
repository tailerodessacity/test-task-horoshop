<?php

namespace App\Command;

use App\Repository\UserRepository;
use App\Service\ApiTokenGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenerateApiTokenCommand extends Command
{
    // Задайте имя команды через конструктор
    protected static $defaultName = 'app:generate-api-token';

    private UserRepository $userRepository;
    private ApiTokenGenerator $tokenGenerator;

    public function __construct(UserRepository $userRepository, ApiTokenGenerator $tokenGenerator)
    {
        parent::__construct('app:generate-api-token');
        $this->userRepository = $userRepository;
        $this->tokenGenerator = $tokenGenerator;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Generates an API token for a user')
            ->addArgument('login', InputArgument::REQUIRED, 'User login')
            ->addOption('revoke', null, InputOption::VALUE_NONE, 'Revoke the user\'s current token');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $login = $input->getArgument('login');
        $user = $this->userRepository->findOneBy(['login' => $login]);

        if (!$user) {
            $io->error(sprintf('User with login "%s" not found.', $login));
            return Command::FAILURE;
        }

        if ($input->getOption('revoke')) {
            $this->tokenGenerator->revokeToken($user);
            $io->success(sprintf('API token revoked for user "%s".', $login));
            return Command::SUCCESS;
        }

        $token = $this->tokenGenerator->generateToken($user);
        $io->success(sprintf('Generated API token for user "%s": %s', $login, $token));

        return Command::SUCCESS;
    }
}
