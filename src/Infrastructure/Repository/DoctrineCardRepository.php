<?php

declare(strict_types=1);

namespace FC\Infrastructure\Repository;

use FC\Domain\Aggregate\CardAggregate\Card;
use FC\Domain\Aggregate\CardAggregate\CardId;
use FC\Domain\Aggregate\CardAggregate\CardRepository;

final class DoctrineCardRepository extends DoctrineRepository implements CardRepository
{
    /**
     * {@inheritDoc}
     */
    public function get(CardId $id): Card
    {
        return $this->find(Card::class, $id);
    }

    /**
     * {@inheritDoc}
     */
    public function save(Card $card): void
    {
        $this->persist($card);
    }

    /**
     * {@inheritDoc}
     */
    public function remove(Card $card): void
    {
        $this->delete($card);
    }
}
