<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserCreatedEvent extends Event
{
    public const NAME = 'user.created';

    public function __construct(private readonly User $user){}

    public function getUser(): User
    {
        return $this->user;
    }
}
