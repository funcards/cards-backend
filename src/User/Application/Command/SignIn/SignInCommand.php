<?php

declare(strict_types=1);

namespace FC\User\Application\Command\SignIn;

use FC\Shared\Application\Command\Command;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(schema="SignIn", required={"email", "password"})
 */
final class SignInCommand implements Command
{
    public const DEFAULT = ['email' => '', 'password' => ''];

    /**
     * @param string $email
     * @param string $password
     */
    public function __construct(
        /** @OA\Property() */
        #[NotBlank, Length(max: 180), Email(mode: Email::VALIDATION_MODE_STRICT)] private string $email,
        /** @OA\Property() */
        #[Length(min: 8, max: 64)] private string $password
    ) {
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
