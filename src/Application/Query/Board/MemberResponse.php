<?php

declare(strict_types=1);

namespace FC\Application\Query\Board;

use FC\Application\Bus\Query\Response;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @OA\Schema(schema="MemberResponse", required={"user_id", "name", "email", "roles"})
 */
final class MemberResponse implements Response
{
    /**
     * @param string $userId
     * @param string $name
     * @param string $email
     * @param array<string> $roles
     */
    public function __construct(
        /** @OA\Property(property="user_id", format="uuid") */ #[SerializedName('user_id')] private string $userId,
        /** @OA\Property() */ private string $name,
        /** @OA\Property() */ private string $email,
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return array<string>
     */
    public function getRoles(): array
    {
        return $this->roles;
    }
}
