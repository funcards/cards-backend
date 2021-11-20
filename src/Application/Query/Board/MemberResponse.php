<?php

declare(strict_types=1);

namespace FC\Application\Query\Board;

use FC\Application\Bus\Query\Response;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @OA\Schema(schema="MemberResponse", required={"user_id", "roles"})
 */
final class MemberResponse implements Response
{
    /**
     * @param string $userId
     * @param array<string> $roles
     */
    public function __construct(
        /** @OA\Property(property="user_id", format="uuid") */ #[SerializedName('user_id')] private string $userId,
        /** @OA\Property() */ private array $roles,
    ) {
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return array<string>
     */
    public function getRoles(): array
    {
        return $this->roles;
    }
}
