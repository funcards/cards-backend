<?php

declare(strict_types=1);

namespace FC\Application\Query\Board;

use FC\Application\Bus\Query\Response;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @OA\Schema(schema="BoardResponse", required={"board_id", "name", "description"})
 */
final class BoardResponse implements Response
{
    /**
     * @param array<string, MemberResponse> $members
     */
    public function __construct(
        /** @OA\Property(property="board_id", format="uuid") */ #[SerializedName('board_id')] private string $boardId,
        /** @OA\Property() */ private string $name,
        /** @OA\Property() */ private string $color,
        /** @OA\Property() */ private string $description,
        /** @OA\Property(property="created_at") */ #[SerializedName('created_at')] private string $createdAt,
        /** @OA\Property(type="object", @OA\AdditionalProperties(ref="#/components/schemas/MemberResponse")) */
        private array $members,
    ) {
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @return array<string, MemberResponse>
     */
    public function getMembers(): array
    {
        return $this->members;
    }
}
