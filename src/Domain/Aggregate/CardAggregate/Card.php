<?php

declare(strict_types=1);

namespace FC\Domain\Aggregate\CardAggregate;

use FC\Domain\Aggregate\AggregateRoot;
use FC\Domain\Aggregate\BoardAggregate\BoardId;
use FC\Domain\Aggregate\EventRecording;

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
