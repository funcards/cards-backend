<?php

namespace FC\User\Application\Command\Refresh;

use FC\Shared\Application\Command\Command;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(schema="RefreshToken", required={"token"})
 */
final class RefreshTokenCommand implements Command
{
    public const DEFAULT = ['token' => ''];

    /**
     * @param string $token
     */
    public function __construct(
        /** @OA\Property() */
        #[NotBlank, Uuid(message: 'Token is not valid.', versions: [Uuid::V4_RANDOM], strict: false)] private string $token,
    ) {
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
}
