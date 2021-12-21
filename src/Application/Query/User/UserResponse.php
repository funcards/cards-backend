<?php

declare(strict_types=1);

namespace FC\Application\Query\User;

use FC\Application\Bus\Query\Response;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @OA\Schema(schema="UserResponse", required={"user_id", "name", "email"})
 */
final class UserResponse implements Response
{
    public function __construct(
        /** @OA\Property(property="user_id", format="uuid") */ #[SerializedName('user_id')] private string $userId,
        /** @OA\Property() */ private string $name,
        /** @OA\Property() */ private string $email,
    ) {
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
