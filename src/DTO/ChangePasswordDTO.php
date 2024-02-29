<?php

namespace App\DTO;

use App\Base\DTO\DTOInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePasswordDTO implements DTOInterface
{
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\PasswordStrength(minScore: Assert\PasswordStrength::STRENGTH_STRONG)]
    private string $password;

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return void
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}