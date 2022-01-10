<?php

declare(strict_types=1);

namespace FC\Application\Command\Board;

use FC\Application\Bus\Command\Command;
use FC\Application\Validator\RolesConstraint;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;

#[Schema(schema: 'AddMember', required: ['member_id', 'roles'])]
final class AddMemberCommand implements Command
{
    public final const DEFAULT = ['member_id' => '', 'roles' => []];

    /**
     * @param array<string> $roles
     */
    public function __construct(
        #[SerializedName('board_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] public readonly string $boardId,
        #[SerializedName('user_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] public readonly string $userId,
        #[Property(property: 'member_id', format: 'uuid'), SerializedName('member_id')]
        #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])]
        public readonly string $memberId,
        #[Property(items: new Items(type: 'string')), RolesConstraint] public readonly array $roles,
    ) {
    }
}
