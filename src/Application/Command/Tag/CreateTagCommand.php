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

#[Schema(schema: 'CreateTag', required: ['name', 'color'])]
final class CreateTagCommand implements Command
{
    public final const DEFAULT = ['name' => '', 'color' => ''];

    public function __construct(
        #[SerializedName('board_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] public readonly string $boardId,
        #[SerializedName('user_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] public readonly string $userId,
        #[SerializedName('tag_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] public readonly string $tagId,
        #[Property, NotBlank, Length(max: 100)] public readonly string $name,
        #[Property, NotBlank, Length(max: 50)] public readonly string $color,
    ) {
    }
}
