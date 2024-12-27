<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class ApiTokenGenerator
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    public function assignToken(User $user): string
    {
        $token = $this->generateToken();

        while ($this->userRepository->findOneBy(['apiToken' => $token])) {
            $token = $this->generateToken();
        }

        $user->setApiToken($token);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $token;
    }

    public function revokeToken(User $user): void
    {
        $user->setApiToken(null);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
