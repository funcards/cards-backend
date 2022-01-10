<?php

declare(strict_types=1);

namespace FC\Application\Command\Tag;

use FC\Application\Bus\Command\Command;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;

#[Schema(schema: 'UpdateTag')]
final class UpdateTagCommand implements Command
{
    public function __construct(
        #[SerializedName('board_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] public readonly string $boardId,
        #[SerializedName('user_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] public readonly string $userId,
        #[SerializedName('tag_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] public readonly string $tagId,
        #[Property, NotBlank(allowNull: true), Length(max: 100)] public readonly ?string $name = null,
        #[Property, NotBlank(allowNull: true), Length(max: 50)] public readonly ?string $color = null,
    ) {
    }
}
