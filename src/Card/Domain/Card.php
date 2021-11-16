<?php

declare(strict_types=1);

namespace FC\Card\Domain;

use FC\Shared\Domain\Aggregate\AggregateRoot;
use FC\Shared\Domain\Aggregate\EventRecording;
use FC\Shared\Domain\ValueObject\Board\BoardId;
use FC\Shared\Domain\ValueObject\Card\CardId;

final class Card implements AggregateRoot
{
    use EventRecording;

    public function __construct(private CardId $id, private BoardId $boardId)
    {
    }

    /**
     * @return CardId
     */
    public function id(): CardId
    {
        return $this->id;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return \sprintf(
            'Card: [id => %s, boardId => %s]',
            $this->id,
            $this->boardId,
        );
    }
}
