<?php

declare(strict_types=1);

namespace FC\Card\Domain;

use FC\Shared\Domain\Exception\NotFoundDomainException;
use FC\Shared\Domain\ValueObject\Card\CardId;

interface CardRepository
{
    /**
     * @param CardId $id
     * @return Card
     * @throws NotFoundDomainException
     */
    public function get(CardId $id): Card;

    /**
     * @param Card $card
     */
    public function save(Card $card): void;
}
