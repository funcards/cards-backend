<?php

namespace FC\Domain\Aggregate\BoardAggregate;

use FC\Domain\Aggregate\Entity;
use FC\Domain\Aggregate\UserAggregate\UserId;
use FC\Domain\ValueObject\CreatedAt;
use FC\Domain\ValueObject\Roles;

final class Member implements Entity
{
    public function __construct(
        private readonly MemberId $id,
        private readonly UserId $userId,
        private Roles $roles,
        private readonly CreatedAt $createdAt,
        private readonly Board $board,
    ) {
    }

    public function id(): MemberId
    {
        return $this->id;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function roles(): Roles
    {
        return $this->roles;
    }

    public function changeRoles(Roles $newRoles): void
    {
        if ($this->roles->isEqualTo($newRoles)) {
            return;
        }

        $this->roles = $newRoles;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return \sprintf(
            'Member [id => %s, boardId => %s, userId => %s, roles => [%s], createdAt => %s]',
            $this->id->asString(),
            $this->board->id()->asString(),
            $this->userId->asString(),
            \implode(', ', $this->roles->toArray()),
            $this->createdAt->asString(),
        );
    }
}
