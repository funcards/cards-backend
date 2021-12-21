<?php

declare(strict_types=1);

namespace FC\Application\Command\Board;

use FC\Application\Bus\Command\Command;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;

/**
 * @OA\Schema(schema="UpdateBoard")
 */
final class UpdateBoardCommand implements Command
{
    public function __construct(
        #[SerializedName('board_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $boardId,
        #[SerializedName('user_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $userId,
        /** @OA\Property() */ #[NotBlank(allowNull: true), Length(max: 150)] private ?string $name = null,
        /** @OA\Property() */ #[NotBlank(allowNull: true), Length(max: 50)] private ?string $color = null,
        /** @OA\Property() */ #[Length(max: 1000)] private ?string $description = null,
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
