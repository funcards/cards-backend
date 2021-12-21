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
 * @OA\Schema(schema="UpdateCard")
 */
final class UpdateCardCommand implements Command
{
    /**
     * @param array<string>|null $tags
     */
    public function __construct(
        #[SerializedName('board_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $boardId,
        #[SerializedName('user_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $userId,
        #[SerializedName('card_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $cardId,
        /** @OA\Property(property="category_id", format="uuid") */
        #[SerializedName('category_id')] #[NotBlank(allowNull: true), Uuid(versions: [Uuid::V4_RANDOM])] private ?string $categoryId = null,
        /** @OA\Property() */ #[NotBlank(allowNull: true), Length(max: 1000)] private ?string $name = null,
        /** @OA\Property() */ #[Length(max: 10000)] private ?string $content = null,
        /** @OA\Property() */ #[Range(min: \PHP_INT_MIN, max: \PHP_INT_MAX)] private ?int $position = null,
        /** @OA\Property(@OA\Items(type="string", format="uuid")) */ #[AllUuidConstraint] private ?array $tags = null,
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

    public function getCardId(): string
    {
        return $this->cardId;
    }

    public function getCategoryId(): ?string
    {
        return $this->categoryId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @return string[]|null
     */
    public function getTags(): ?array
    {
        return $this->tags;
    }
}
