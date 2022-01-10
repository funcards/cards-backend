<?php

declare(strict_types=1);

namespace FC\Application\Command\Auth;

use FC\Application\Bus\Command\Command;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SignUp', required: ['name', 'email', 'password'])]
final class SignUpCommand implements Command
{
    public final const DEFAULT = ['name' => '', 'email' => '', 'password' => '', 'roles' => ['ROLE_API_USER']];

    /**
     * @param array<string> $roles
     */
    public function __construct(
        #[OA\Property, NotBlank, Length(min: 3, max: 100)] public readonly string $name,
        #[OA\Property, NotBlank, Length(max: 180), Email(mode: Email::VALIDATION_MODE_STRICT)]
        public readonly string $email,
        #[OA\Property, NotBlank, Length(min: 8, max: 64)] public readonly string $password,
        public readonly array $roles,
    ) {
    }
}
