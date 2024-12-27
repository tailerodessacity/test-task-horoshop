<?php

namespace App\Service;

use App\DTO\UserDTO;
use App\Entity\User;

interface UserServiceInterface
{
    public function createUser(UserDTO $dto): User;

    public function updateUser(User $user, UserDTO $dto): User;
}
