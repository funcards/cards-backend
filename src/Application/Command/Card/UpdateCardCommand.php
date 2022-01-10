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

#[Schema(schema: 'UpdateCard')]
final class UpdateCardCommand implements Command
{
    /**
     * @param array<string>|null $tags
     */
    public function __construct(
        #[SerializedName('board_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] public readonly string $boardId,
        #[SerializedName('user_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] public readonly string $userId,
        #[SerializedName('card_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] public readonly string $cardId,
        #[Property(property: 'category_id', format: 'uuid'), SerializedName('category_id')]
        #[NotBlank(allowNull: true), Uuid(versions: [Uuid::V4_RANDOM])]
        public readonly ?string $categoryId = null,
        #[Property, NotBlank(allowNull: true), Length(max: 1000)] public readonly ?string $name = null,
        #[Property, Length(max: 10000)] public readonly ?string $content = null,
        #[Property, Range(min: \PHP_INT_MIN, max: \PHP_INT_MAX)] public readonly ?int $position = null,
        #[Property(items: new Items(type: 'string', format: 'uuid')), AllUuidConstraint]
        public readonly ?array $tags = null,
    ) {
    }
}
