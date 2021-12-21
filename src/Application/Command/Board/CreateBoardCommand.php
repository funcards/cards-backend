<?php

declare(strict_types=1);

namespace FC\Application\Command\Board;

use FC\Application\Bus\Command\Command;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(schema="CreateBoard", required={"name", "color"})
 */
final class CreateBoardCommand implements Command
{
    public const DEFAULT = ['name' => '', 'color' => '', 'description' => ''];

    public function __construct(
        #[SerializedName('board_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $boardId,
        #[SerializedName('owner_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $ownerId,
        /** @OA\Property() */ #[NotBlank, Length(max: 150)] private string $name,
        /** @OA\Property() */ #[NotBlank, Length(max: 50)] private string $color,
        /** @OA\Property() */ #[Length(max: 1000)] private string $description,
    ) {
    }

    public function getBoardId(): string
    {
        return $this->boardId;
    }

    public function getOwnerId(): string
    {
        return $this->ownerId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
