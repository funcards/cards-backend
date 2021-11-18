<?php

declare(strict_types=1);

namespace FC\Infrastructure\Mapping\Card;

use FC\Domain\Aggregate\CardAggregate\CardId;
use FC\Infrastructure\Mapping\IdType;

final class CardIdType extends IdType
{
    /**
     * {@inheritDoc}
     */
    protected function getIdClass(): string
    {
        return CardId::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'card_id';
    }
}
