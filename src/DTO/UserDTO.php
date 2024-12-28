<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UserDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 5,
        max: 8,
        minMessage: "Login must be at least 5 characters long.",
        maxMessage: "Login must be max 8 characters long.")
    ]
    public string $login;

    #[Assert\NotBlank]
    #[Assert\Regex(pattern: "/^\+\d{10,15}$/", message: "Phone number must be in international format.")]
    public string $phone;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 6,
        max: 8,
        minMessage: "Password must be at least 6 characters long.",
        maxMessage: "Password must be max 8 characters long.")
    ]
    public string $password;

    #[Assert\NotBlank]
    public array $roles;

    public function __construct(
        string $login,
        string $phone,
        string $password,
        array $roles = ['ROLE_USER']
    ) {
        $this->login = $login;
        $this->phone = $phone;
        $this->password = $password;
        $this->roles = $roles;
    }
}

