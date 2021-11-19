<?php

declare(strict_types=1);

namespace FC\Application\Query\Board;

use FC\Application\Bus\Query\Response;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(schema="BoardResponse", required={"id", "name", "description"})
 */
final class BoardResponse implements Response
{
    /**
     * @param string $boardId
     * @param string $name
     * @param string $color
     * @param string $description
     * @param string $createdAt
     * @param array<MemberResponse> $members
     */
    public function __construct(
        /** @OA\Property(property="board_id", format="uuid") */ private string $boardId,
        /** @OA\Property() */ private string $name,
        /** @OA\Property() */ private string $color,
        /** @OA\Property() */ private string $description,
        /** @OA\Property(property="created_at") */ private string $createdAt,
        /** @OA\Property(@OA\Items(ref="#/components/schemas/MemberResponse")) */ private array $members,
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

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @return MemberResponse[]
     */
    public function getMembers(): array
    {
        return $this->members;
    }
}
