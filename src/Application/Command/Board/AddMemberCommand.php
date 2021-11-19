<?php

declare(strict_types=1);

namespace FC\Application\Command\Board;

use FC\Application\Bus\Command\Command;
use FC\Application\Validator\RolesConstraint;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;

/**
 * @OA\Schema(schema="AddMember", required={"member_id", "roles"})
 */
final class AddMemberCommand implements Command
{
    public const DEFAULT = ['member_id' => '', 'roles' => []];

    /**
     * @param string $boardId
     * @param string $userId
     * @param string $memberId
     * @param array<string> $roles
     */
    public function __construct(
        #[SerializedName('board_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $boardId,
        #[SerializedName('user_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $userId,
        /** @OA\Property(property="member_id") */
        #[SerializedName('member_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $memberId,
        /** @OA\Property(@OA\Items(type="string")) */ #[RolesConstraint] private array $roles,
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
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getMemberId(): string
    {
        return $this->memberId;
    }

    /**
     * @return array<string>
     */
    public function getRoles(): array
    {
        return $this->roles;
    }
}
