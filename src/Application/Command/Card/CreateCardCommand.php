<?php

declare(strict_types=1);

namespace FC\Application\Command\Card;

use FC\Application\Bus\Command\Command;
use FC\Application\Validator\AllUuidConstraint;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Uuid;

/**
 * @OA\Schema(schema="CreateCard", required={"category_id", "name", "position"})
 */
final class CreateCardCommand implements Command
{
    public const DEFAULT = ['category_id' => '', 'name' => '', 'content' => '', 'position' => 0, 'tags' => []];

    /**
     * @param string $boardId
     * @param string $userId
     * @param string $cardId
     * @param string $categoryId
     * @param string $name
     * @param string $content
     * @param int $position
     * @param array<string> $tags
     */
    public function __construct(
        #[SerializedName('board_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $boardId,
        #[SerializedName('user_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $userId,
        #[SerializedName('card_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $cardId,
        /** @OA\Property(property="category_id", format="uuid") */
        #[SerializedName('category_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $categoryId,
        /** @OA\Property() */ #[NotBlank, Length(max: 1000)] private string $name,
        /** @OA\Property() */ #[Length(max: 10000)] private string $content,
        /** @OA\Property() */ #[Range(min: \PHP_INT_MIN, max: \PHP_INT_MAX)] private int $position,
        /** @OA\Property(@OA\Items(type="string", format="uuid")) */ #[AllUuidConstraint] private array $tags,
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
    public function getCardId(): string
    {
        return $this->cardId;
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
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }
}
