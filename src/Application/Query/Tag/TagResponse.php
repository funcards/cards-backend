<?php

declare(strict_types=1);

namespace FC\Application\Query\Tag;

use FC\Application\Bus\Query\Response;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[Schema(schema: 'TagResponse', required: ['tag_id', 'board_id', 'name', 'color'])]
final class TagResponse implements Response
{
    public function __construct(
        #[Property(property: 'tag_id', format: 'uuid'), SerializedName('tag_id')] public readonly string $tagId,
        #[Property(property: 'board_id', format: 'uuid'), SerializedName('board_id')] public readonly string $boardId,
        #[Property] public readonly string $name,
        #[Property] public readonly string $color,
    ) {
    }
}
