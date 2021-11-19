<?php

declare(strict_types=1);

namespace FC\Application\Query\Card;

use FC\Application\Bus\Query\Response;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(schema="CardResponse", required={"id", "board_id", "category_id", "name", "content", "tags"})
 */
final class CardResponse implements Response
{
    /**
     * @param string $cardId
     * @param string $boardId
     * @param string $categoryId
     * @param string $name
     * @param string $content
     * @param array<string> $tags
     */
    public function __construct(
        /** @OA\Property(property="card_id", format="uuid") */ private string $cardId,
        /** @OA\Property(property="board_id", format="uuid") */ private string $boardId,
        /** @OA\Property(property="category_id", format="uuid") */ private string $categoryId,
        /** @OA\Property() */ private string $name,
        /** @OA\Property() */ private string $content,
        /** @OA\Property(@OA\Items(type="string", format="uuid")) */ private array $tags,
    ) {
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
    public function getBoardId(): string
    {
        return $this->boardId;
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
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }
}
