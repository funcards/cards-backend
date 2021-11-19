<?php

declare(strict_types=1);

namespace FC\Application\Query\Category;

use FC\Application\Bus\Query\Response;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(schema="CategoryResponse", required={"id", "board_id", "name", "position"})
 */
final class CategoryResponse implements Response
{
    /**
     * @param string $categoryId
     * @param string $boardId
     * @param string $name
     * @param int $position
     */
    public function __construct(
        /** @OA\Property(property="category_id", format="uuid") */ private string $categoryId,
        /** @OA\Property(property="board_id", format="uuid") */ private string $boardId,
        /** @OA\Property() */ private string $name,
        /** @OA\Property() */ private int $position,
    ) {
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
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }
}
