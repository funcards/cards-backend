<?php

declare(strict_types=1);

namespace FC\Application\Command\Board;

use FC\Application\Bus\Command\Command;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;

#[Schema(schema: 'CreateBoard', required: ['name', 'color'])]
final class CreateBoardCommand implements Command
{
    public final const DEFAULT = ['name' => '', 'color' => '', 'description' => ''];

    public function __construct(
        #[SerializedName('board_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] public readonly string $boardId,
        #[SerializedName('owner_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] public readonly string $ownerId,
        #[Property, NotBlank, Length(max: 150)] public readonly string $name,
        #[Property, NotBlank, Length(max: 50)] public readonly string $color,
        #[Property, Length(max: 1000)] public readonly string $description,
    ) {
    }
}
