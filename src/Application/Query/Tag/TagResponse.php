<?php

declare(strict_types=1);

namespace FC\Application\Query\Tag;

use FC\Application\Bus\Query\Response;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @OA\Schema(schema="TagResponse", required={"tag_id", "board_id", "name", "color"})
 */
final class TagResponse implements Response
{
    public function __construct(
        /** @OA\Property(property="tag_id", format="uuid") */ #[SerializedName('tag_id')] private string $tagId,
        /** @OA\Property(property="board_id", format="uuid") */ #[SerializedName('board_id')] private string $boardId,
        /** @OA\Property() */ private string $name,
        /** @OA\Property() */ private string $color,
    ) {
    }

    public function getTagId(): string
    {
        return $this->tagId;
    }

    public function getBoardId(): string
    {
        return $this->boardId;
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
