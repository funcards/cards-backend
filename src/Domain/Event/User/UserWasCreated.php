<?php

declare(strict_types=1);

namespace FC\Domain\Event\User;

use FC\Domain\Event\DomainEvent;

final class UserWasCreated extends DomainEvent
{
    /**
     * @param string $aggregateId
     * @param string $occurredOn
     * @param string $name
     * @param string $email
     * @param string $password
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
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
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
