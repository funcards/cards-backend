<?php

declare(strict_types=1);

namespace FC\Application\Query\Tag;

use FC\Application\Bus\Query\Response;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(schema="TagResponse", required={"id", "board_id", "name", "color"})
 */
final class TagResponse implements Response
{
    /**
     * @param string $tagId
     * @param string $boardId
     * @param string $name
     * @param string $color
     */
    public function __construct(
        /** @OA\Property(property="tag_id", format="uuid") */ private string $tagId,
        /** @OA\Property(property="board_id", format="uuid") */ private string $boardId,
        /** @OA\Property() */ private string $name,
        /** @OA\Property() */ private string $color,
    ) {
    }

    /**
     * @return string
     */
    public function getTagId(): string
    {
        return $this->tagId;
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
}