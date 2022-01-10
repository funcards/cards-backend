<?php

declare(strict_types=1);

namespace FC\Application\Query\Category;

use FC\Application\Bus\Query\Response;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[Schema(schema: 'CategoryResponse', required: ['category_id', 'board_id', 'name', 'position'])]
final class CategoryResponse implements Response
{
    public function __construct(
        #[Property(property: 'category_id', format: 'uuid'), SerializedName('category_id')]
        public readonly string $categoryId,
        #[Property(property: 'board_id', format: 'uuid'), SerializedName('board_id')] public readonly string $boardId,
        #[Property] public readonly string $name,
        #[Property] public readonly int $position,
    ) {
    }
}
