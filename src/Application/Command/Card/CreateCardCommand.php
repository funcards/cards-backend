<?php

declare(strict_types=1);

namespace FC\Application\Command\Card;

use FC\Application\Bus\Command\Command;
use FC\Application\Validator\AllUuidConstraint;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Uuid;

#[Schema(schema: 'CreateCard', required: ['category_id', 'name', 'position'])]
final class CreateCardCommand implements Command
{
    public final const DEFAULT = ['category_id' => '', 'name' => '', 'content' => '', 'position' => 0, 'tags' => []];

    /**
     * @param array<string> $tags
     */
    public function __construct(
        #[SerializedName('board_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] public readonly string $boardId,
        #[SerializedName('user_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] public readonly string $userId,
        #[SerializedName('card_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] public readonly string $cardId,
        #[Property(property: 'category_id', format: 'uuid'), SerializedName('category_id')]
        #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])]
        public readonly string $categoryId,
        #[Property, NotBlank, Length(max: 1000)] public readonly string $name,
        #[Property, Length(max: 10000)] public readonly string $content,
        #[Property, Range(min: \PHP_INT_MIN, max: \PHP_INT_MAX)] public readonly int $position,
        #[Property(items: new Items(type: 'string', format: 'uuid')), AllUuidConstraint]
        public readonly array $tags,
    ) {
    }
}
