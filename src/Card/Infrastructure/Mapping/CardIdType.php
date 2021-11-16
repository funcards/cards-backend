<?php

declare(strict_types=1);

namespace FC\Card\Infrastructure\Mapping;

use FC\Shared\Domain\ValueObject\Card\CardId;
use FC\Shared\Infrastructure\Mapping\IdType;

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
