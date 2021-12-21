<?php

declare(strict_types=1);

namespace FC\Application\Command\Category;

use FC\Application\Bus\Command\Command;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Uuid;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(schema="UpdateCategory")
 */
final class UpdateCategoryCommand implements Command
{
    public function __construct(
        #[SerializedName('board_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $boardId,
        #[SerializedName('user_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $userId,
        #[SerializedName('category_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $categoryId,
        /** @OA\Property() */ #[NotBlank(allowNull: true), Length(max: 150)] private ?string $name = null,
        /** @OA\Property() */ #[Range(min: \PHP_INT_MIN, max: \PHP_INT_MAX)] private ?int $position = null,
    ) {
    }

    public function getBoardId(): string
    {
        return $this->boardId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getCategoryId(): string
    {
        return $this->categoryId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }
}
