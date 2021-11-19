<?php

declare(strict_types=1);

namespace FC\Domain\Aggregate\CardAggregate;

use FC\Domain\Exception\NotFoundException;

interface CardRepository
{
    /**
     * @param CardId $id
     * @return Card
     * @throws NotFoundException
     */
    public function get(CardId $id): Card;

    /**
     * @param Card $card
     */
    public function save(Card $card): void;

    /**
     * @param Card $card
     */
    public function remove(Card $card): void;
}
