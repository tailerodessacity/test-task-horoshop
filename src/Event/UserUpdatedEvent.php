<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserUpdatedEvent extends Event
{
    public const NAME = 'user.updated';

    public function __construct(private readonly User $user){}

    public function getUser(): User
    {
        return $this->user;
    }
}
