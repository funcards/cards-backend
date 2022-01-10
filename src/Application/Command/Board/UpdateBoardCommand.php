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

#[Schema(schema: 'UpdateBoard')]
final class UpdateBoardCommand implements Command
{
    public function __construct(
        #[SerializedName('board_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] public readonly string $boardId,
        #[SerializedName('user_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] public readonly string $userId,
        #[Property, NotBlank(allowNull: true), Length(max: 150)] public readonly ?string $name = null,
        #[Property, NotBlank(allowNull: true), Length(max: 50)] public readonly ?string $color = null,
        #[Property, Length(max: 1000)] public readonly ?string $description = null,
    ) {
    }
}
