<?php
// tests/Service/UserServiceTest.php

namespace App\Tests\Service;

use App\DTO\UserDTO;
use App\Event\UserCreatedEvent;
use App\Event\UserUpdatedEvent;
use App\Event\UserDeletedEvent;
use App\Repository\UserRepository;
use App\Service\UserService;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Psr\Log\LoggerInterface;

class UserServiceTest extends TestCase
{
    public function testCreateUserDispatchesEvent(): void
    {
        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $userRepository = $this->createMock(UserRepository::class);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);

        $passwordHasher->method('hashPassword')->willReturn('hashed_password');

        $eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->isInstanceOf(UserCreatedEvent::class),
                UserCreatedEvent::NAME
            );

        $userService = new UserService($passwordHasher, $userRepository, $eventDispatcher);

        $dto = new UserDTO('testuser', '+12345678901', 'secret', ['ROLE_USER']);

        $userService->createUser($dto);
    }

    public function testUpdateUserDispatchesEvent(): void
    {
        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $userRepository = $this->createMock(UserRepository::class);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);

        $passwordHasher->method('hashPassword')->willReturn('hashed_password');

        $user = new User();
        $user->setLogin('olduser');
        $user->setPhone('+12345678901');
        $user->setPass('old_password');
        $user->setRoles(['ROLE_USER']);

        $userRepository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (User $savedUser) use ($user) {
                return $savedUser === $user;
            }));

        $eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->isInstanceOf(UserUpdatedEvent::class),
                UserUpdatedEvent::NAME
            );

        $userService = new UserService($passwordHasher, $userRepository, $eventDispatcher);

        $dto = new UserDTO('newuser', '+19876543210', 'new_secret', ['ROLE_ADMIN']);

        $userService->updateUser($user, $dto);
    }

    public function testDeleteUserDispatchesEvent(): void
    {
        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $userRepository = $this->createMock(UserRepository::class);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);

        $user = new User();
        $user->setLogin('deleteuser');
        $user->setPhone('+12345678901');
        $user->setPass('password');
        $user->setRoles(['ROLE_USER']);

        $userRepository->expects($this->once())
            ->method('remove')
            ->with($this->callback(function (User $removedUser) use ($user) {
                return $removedUser === $user;
            }));

        $eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->isInstanceOf(UserDeletedEvent::class),
                UserDeletedEvent::NAME
            );

        $userService = new UserService($passwordHasher, $userRepository, $eventDispatcher);

        $userService->deleteUser($user);
    }
}
