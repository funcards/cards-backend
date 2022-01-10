<?php

declare(strict_types=1);

namespace FC\Application\Query\Card;

use FC\Application\Bus\Query\Response;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[Schema(schema: 'CardResponse', required: [
    'card_id',
    'board_id',
    'category_id',
    'name',
    'content',
    'position',
    'tags'
])]
final class CardResponse implements Response
{
    /**
     * @param array<string> $tags
     */
    public function __construct(
        #[Property(property: 'card_id', format: 'uuid'), SerializedName('card_id')] public readonly string $cardId,
        #[Property(property: 'board_id', format: 'uuid'), SerializedName('board_id')] public readonly string $boardId,
        #[Property(property: 'category_id', format: 'uuid'), SerializedName('category_id')]
        public readonly string $categoryId,
        #[Property] public readonly string $name,
        #[Property] public readonly string $content,
        #[Property] public readonly int $position,
        #[Property(items: new Items(type: 'string', format: 'uuid'))] public readonly array $tags,
    ) {
    }
}
