<?php

declare(strict_types=1);

namespace FC\Domain\Event\Board;

use FC\Domain\Event\DomainEvent;

final class BoardWasCreated extends DomainEvent
{
    public function __construct(
        string $aggregateId,
        string $occurredOn,
        private string $ownerId,
        private string $name,
        private string $color,
        private ?string $description,
    ) {
        parent::__construct($aggregateId, $occurredOn);
    }

    public function getOwnerId(): string
    {
        return $this->ownerId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
