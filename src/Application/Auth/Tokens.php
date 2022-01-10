<?php

declare(strict_types=1);

namespace FC\Application\Auth;

use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[OA\Schema(schema: 'Tokens', required: ['access_token', 'refresh_token'])]
final class Tokens
{
    public function __construct(
        #[OA\Property('access_token'), SerializedName('access_token')] public readonly string $accessToken,
        #[OA\Property('refresh_token'), SerializedName('refresh_token')] public readonly string $refreshToken,
    ) {
    }
}
