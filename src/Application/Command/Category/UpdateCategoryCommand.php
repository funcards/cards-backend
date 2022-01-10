<?php

declare(strict_types=1);

namespace FC\Application\Command\Category;

use FC\Application\Bus\Command\Command;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Uuid;

#[Schema(schema: 'UpdateCategory')]
final class UpdateCategoryCommand implements Command
{
    public function __construct(
        #[SerializedName('board_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] public readonly string $boardId,
        #[SerializedName('user_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] public readonly string $userId,
        #[SerializedName('category_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] public readonly string $categoryId,
        #[Property, NotBlank(allowNull: true), Length(max: 150)] public readonly ?string $name = null,
        #[Property, Range(min: \PHP_INT_MIN, max: \PHP_INT_MAX)] public readonly ?int $position = null,
    ) {
    }
}
