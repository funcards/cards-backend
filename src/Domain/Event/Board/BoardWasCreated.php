<?php

declare(strict_types=1);

namespace FC\Domain\Event\Board;

use FC\Domain\Event\DomainEvent;

final class BoardWasCreated extends DomainEvent
{
    /**
     * @param string $aggregateId
     * @param string $occurredOn
     * @param string $ownerId
     * @param string $name
     * @param string $color
     * @param string|null $description
     */
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

    /**
     * @return string
     */
    public function getOwnerId(): string
    {
        return $this->ownerId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }
}
