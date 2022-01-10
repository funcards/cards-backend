<?php

declare(strict_types=1);

namespace FC\Application\Query\Board;

use FC\Application\Bus\Query\Response;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[Schema(schema: 'MemberResponse', required: ['user_id', 'name', 'email', 'roles'])]
final class MemberResponse implements Response
{
    /**
     * @param array<string> $roles
     */
    public function __construct(
        #[Property(property: 'user_id', format: 'uuid'), SerializedName('user_id')] public readonly string $userId,
        #[Property] public readonly string $name,
        #[Property] public readonly string $email,
        #[Property(items: new Items(type: 'string'))] public readonly array $roles,
    ) {
    }
}
