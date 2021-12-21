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
 * @OA\Schema(schema="CreateTag", required={"name", "color"})
 */
final class CreateTagCommand implements Command
{
    public const DEFAULT = ['name' => '', 'color' => ''];

    public function __construct(
        #[SerializedName('board_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $boardId,
        #[SerializedName('user_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $userId,
        #[SerializedName('tag_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $tagId,
        /** @OA\Property() */ #[NotBlank, Length(max: 100)] private string $name,
        /** @OA\Property() */ #[NotBlank, Length(max: 50)] private string $color,
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

    public function getName(): string
    {
        return $this->name;
    }

    public function getColor(): string
    {
        return $this->color;
    }
}
