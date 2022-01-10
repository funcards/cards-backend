<?php

declare(strict_types=1);

namespace FC\Application\Query\Board;

use FC\Application\Bus\Query\Response;
use FC\UI\Http\Rest\OpenApi\OAProperty;
use OpenApi\Attributes\AdditionalProperties;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[Schema(schema: 'BoardResponse', required: ['board_id', 'name', 'description'])]
final class BoardResponse implements Response
{
    /**
     * @param array<string, MemberResponse> $members
     */
    public function __construct(
        #[Property(property: 'board_id', format: 'uuid'), SerializedName('board_id')] public readonly string $boardId,
        #[Property] public readonly string $name,
        #[Property] public readonly string $color,
        #[Property] public readonly string $description,
        #[Property(property: 'created_at'), SerializedName('created_at')] public readonly string $createdAt,
        #[OAProperty(type: 'object', additionalProperties: new AdditionalProperties(ref: '#/components/schemas/MemberResponse'))]
        public readonly array $members,
    ) {
    }
}
