<?php

declare(strict_types=1);

namespace FC\Application\Command\Tag;

use FC\Application\Bus\Command\Command;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(schema="UpdateTag")
 */
final class UpdateTagCommand implements Command
{
    public function __construct(
        #[SerializedName('board_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $boardId,
        #[SerializedName('user_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $userId,
        #[SerializedName('tag_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $tagId,
        /** @OA\Property() */ #[NotBlank(allowNull: true), Length(max: 100)] private ?string $name = null,
        /** @OA\Property() */ #[NotBlank(allowNull: true), Length(max: 50)] private ?string $color = null,
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

    public function getTagId(): string
    {
        return $this->tagId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }
}
