<?php

namespace App\Service;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Event\UserCreatedEvent;
use App\Event\UserUpdatedEvent;
use App\Event\UserDeletedEvent;
use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService implements UserServiceInterface
{
    private UserPasswordHasherInterface $passwordHasher;
    private UserRepository $userRepository;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->passwordHasher = $passwordHasher;
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function createUser(UserDTO $dto): User
    {
        $user = new User();
        $user->setLogin($dto->login);
        $user->setPhone($dto->phone);
        $user->setPass($this->passwordHasher->hashPassword($user, $dto->password));
        $user->setRoles($dto->roles);
        $user->setApiToken(bin2hex(random_bytes(32)));

        $this->userRepository->save($user);

        $event = new UserCreatedEvent($user);
        $this->eventDispatcher->dispatch($event, UserCreatedEvent::NAME);

        return $user;
    }

    public function updateUser(User $user, UserDTO $dto): User
    {
        $user->setLogin($dto->login);
        $user->setPhone($dto->phone);
        $user->setPass($this->passwordHasher->hashPassword($user, $dto->password));

        $this->userRepository->save($user);

        $event = new UserUpdatedEvent($user);
        $this->eventDispatcher->dispatch($event, UserUpdatedEvent::NAME);

        return $user;
    }

    public function deleteUser(User $user): void
    {
        $this->userRepository->remove($user);

        $event = new UserDeletedEvent($user);
        $this->eventDispatcher->dispatch($event, UserDeletedEvent::NAME);
    }
}
