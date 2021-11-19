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
 * @OA\Schema(schema="CreateCategory", required={"name", "position"})
 */
final class CreateCategoryCommand implements Command
{
    public const DEFAULT = ['name' => '', 'position' => 0];

    /**
     * @param string $boardId
     * @param string $userId
     * @param string $categoryId
     * @param string $name
     * @param int $position
     */
    public function __construct(
        #[SerializedName('board_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $boardId,
        #[SerializedName('user_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $userId,
        #[SerializedName('category_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $categoryId,
        /** @OA\Property() */ #[NotBlank, Length(max: 150)] private string $name,
        /** @OA\Property() */ #[Range(min: \PHP_INT_MIN, max: \PHP_INT_MAX)] private int $position,
    ) {
    }

    /**
     * @return string
     */
    public function getBoardId(): string
    {
        return $this->boardId;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getCategoryId(): string
    {
        return $this->categoryId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }
}
