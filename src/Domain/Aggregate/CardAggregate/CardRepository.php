<?php

declare(strict_types=1);

namespace FC\Domain\Aggregate\CardAggregate;

use FC\Domain\Exception\NotFoundException;

interface CardRepository
{
    /**
     * @throws NotFoundException
     */
    public function get(CardId $id): Card;

    public function save(Card $card): void;

    public function remove(Card $card): void;
}
