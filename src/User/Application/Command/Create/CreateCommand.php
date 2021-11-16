<?php

declare(strict_types=1);

namespace FC\User\Application\Command\Create;

use FC\Shared\Application\Command\Command;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(schema="SignUp", required={"name", "email", "password"})
 */
final class CreateCommand implements Command
{
    public const DEFAULT = ['name' => '', 'email' => '', 'password' => '', 'roles' => ['ROLE_USER']];

    /**
     * @param string $name
     * @param string $email
     * @param string $password
     * @param array<string> $roles
     */
    public function __construct(
        /** @OA\Property() */
        #[Length(min: 3, max: 100)] private string $name,
        /** @OA\Property() */
        #[NotBlank, Length(max: 180), Email(mode: Email::VALIDATION_MODE_STRICT)] private string $email,
        /** @OA\Property() */
        #[Length(min: 8, max: 64)] private string $password,
        private array $roles,
    ) {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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

    /**
     * @return array<string>
     */
    public function getRoles(): array
    {
        return $this->roles;
    }
}
