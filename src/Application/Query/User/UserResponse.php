<?php

declare(strict_types=1);

namespace FC\Application\Query\User;

use FC\Application\Bus\Query\Response;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(schema="UserResponse", required={"id", "name", "email"})
 */
final class UserResponse implements Response
{
    /**
     * @param string $userId
     * @param string $name
     * @param string $email
     */
    public function __construct(
        /** @OA\Property(property="user_id", format="uuid") */ private string $userId,
        /** @OA\Property() */ private string $name,
        /** @OA\Property() */ private string $email,
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
}
