<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users', uniqueConstraints: [
    new ORM\UniqueConstraint(name: 'api_token_unique', columns: ['api_token']),
])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['user_list', 'user_detail', 'user_show'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 8, unique: true)]
    #[Assert\Length(min: 5, max: 8)]
    #[Groups(['user_list', 'user_detail', 'user_show'])]
    private ?string $login = null;

    #[ORM\Column(type: Types::STRING, length: 8)]
    #[Assert\Length(max: 8)]
    #[Groups(['user_detail', 'user_show'])]
    private ?string $phone = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $pass = null;

    #[ORM\Column(name: 'api_token', type: Types::STRING, length: 255, nullable: true, unique: true)]
    #[Groups(['user_sensitive'])]
    private ?string $apiToken = null;

    #[ORM\Column(type: Types::JSON)]
    #[Groups(['user_list', 'user_detail', 'user_show'])]
    private array $roles = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(?string $login): self
    {
        $this->login = $login;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getPass(): ?string
    {
        return $this->pass;
    }

    public function setPass(?string $pass): self
    {
        $this->pass = $pass;
        return $this;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(?string $apiToken): self
    {
        $this->apiToken = $apiToken;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->pass;
    }

    public function getUserIdentifier(): string
    {
        return $this->login;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {

    }
}
