<?php

declare(strict_types=1);

namespace FC\Application\Query\User;

use FC\Application\Bus\Query\Response;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[Schema(schema: 'UserResponse', required: ['user_id', 'name', 'email'])]
final class UserResponse implements Response
{
    public function __construct(
        #[Property(property: 'user_id', format: 'uuid'), SerializedName('user_id')] public readonly string $userId,
        #[Property] public readonly string $name,
        #[Property] public readonly string $email,
    ) {
    }
}
