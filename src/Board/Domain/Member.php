<?php

namespace FC\Board\Domain;

use Assert\Assert;
use FC\Shared\Domain\Entity;
use FC\Shared\Domain\ValueObject\Board\MemberId;
use FC\Shared\Domain\ValueObject\CreatedAt;
use FC\Shared\Domain\ValueObject\Roles;
use FC\Shared\Domain\ValueObject\User\UserId;

final class Member implements Entity
{
    /**
     * @param MemberId $id
     * @param UserId $userId
     * @param Roles $roles
     * @param CreatedAt $createdAt
     * @param Board $board
     */
    public function __construct(
        private MemberId $id,
        private UserId $userId,
        private Roles $roles,
        private CreatedAt $createdAt,
        private Board $board,
    ) {
    }

    /**
     * @return MemberId
     */
    public function id(): MemberId
    {
        return $this->id;
    }

    /**
     * @return UserId
     */
    public function userId(): UserId
    {
        return $this->userId;
    }

    /**
     * @return Roles
     */
    public function roles(): Roles
    {
        return $this->roles;
    }

    /**
     * @param Roles $newRoles
     */
    public function changeRoles(Roles $newRoles): void
    {
        Assert::that($newRoles->toArray())->notEq($this->roles->toArray());

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
