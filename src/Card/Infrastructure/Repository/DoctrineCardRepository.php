<?php

declare(strict_types=1);

namespace FC\Card\Infrastructure\Repository;

use FC\Card\Domain\Card;
use FC\Card\Domain\CardRepository;
use FC\Shared\Domain\ValueObject\Card\CardId;
use FC\Shared\Infrastructure\Repository\DoctrineRepository;

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
}
