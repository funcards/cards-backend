<?php

declare(strict_types=1);

namespace FC\Application\Command\Category;

use FC\Application\Bus\Command\Command;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;

final class RemoveCategoryCommand implements Command
{
    public function __construct(
        #[SerializedName('board_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] public readonly string $boardId,
        #[SerializedName('user_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] public readonly string $userId,
        #[SerializedName('category_id')] #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] public readonly string $categoryId,
    ) {
    }
}
