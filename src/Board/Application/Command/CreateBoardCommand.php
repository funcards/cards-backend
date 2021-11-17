<?php

declare(strict_types=1);

namespace FC\Board\Application\Command;

use FC\Shared\Application\Command\Command;
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

    /**
     * @param string $boardId
     * @param string $ownerId
     * @param string $name
     * @param string $color
     * @param string $description
     */
    public function __construct(
        #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $boardId,
        #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $ownerId,
        /** @OA\Property() */ #[NotBlank, Length(max: 150)] private string $name,
        /** @OA\Property() */ #[NotBlank, Length(max: 50)] private string $color,
        /** @OA\Property() */ #[Length(max: 1000)] private string $description,
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
    public function getOwnerId(): string
    {
        return $this->ownerId;
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
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}
