<?php

namespace App\DTO;

use App\Base\DTO\DTOInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UserDTO implements DTOInterface
{
    private ?int $id;
    #[Assert\NotBlank(groups: ['Create'])]
    #[Assert\Length(min: 1, max: 64)]
    private string $first_name;
    #[Assert\NotBlank(groups: ['Create'])]
    #[Assert\Length(min: 1, max: 64)]
    private string $last_name;
    #[Assert\NotBlank(groups: ['Create'])]
    #[Assert\Length(min: 6, max: 64, groups: ['Create'])]
    #[Assert\PasswordStrength]
    private string $password;
    #[Assert\Email]
    #[Assert\NotBlank(groups: ['Create'])]
    #[Assert\Length(min: 1, max: 64)]
    private string $email;

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }
}