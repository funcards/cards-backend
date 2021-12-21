<?php

declare(strict_types=1);

namespace FC\Domain\Event\User;

use FC\Domain\Event\DomainEvent;

final class UserWasCreated extends DomainEvent
{
    /**
     * @param array<string> $roles
     */
    public function __construct(
        string $aggregateId,
        string $occurredOn,
        private string $name,
        private string $email,
        private string $password,
        private array $roles,
    ) {
        parent::__construct($aggregateId, $occurredOn);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }
}
