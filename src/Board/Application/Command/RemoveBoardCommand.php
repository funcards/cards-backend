<?php

declare(strict_types=1);

namespace FC\Board\Application\Command;

use FC\Shared\Application\Command\Command;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;

final class RemoveBoardCommand implements Command
{
    /**
     * @param string $boardId
     * @param string $userId
     */
    public function __construct(
        #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $boardId,
        #[NotBlank, Uuid(versions: [Uuid::V4_RANDOM])] private string $userId,
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
}
