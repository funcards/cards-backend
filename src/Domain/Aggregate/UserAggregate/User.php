<?php

declare(strict_types=1);

namespace FC\Domain\Aggregate\UserAggregate;

use FC\Domain\ValueObject\CreatedAt;
use FC\Domain\ValueObject\Role;
use FC\Domain\ValueObject\Roles;
use FC\Domain\ValueObject\UpdatedAt;
use FC\Domain\Event\User\UserEmailWasChanged;
use FC\Domain\Event\User\UserNameWasChanged;
use FC\Domain\Event\User\UserPasswordWasChanged;
use FC\Domain\Event\User\UserRolesWasChanged;
use FC\Domain\Event\User\UserWasCreated;
use FC\Domain\Aggregate\AggregateRoot;
use FC\Domain\Aggregate\EventRecording;
use JetBrains\PhpStorm\Pure;

final class User implements AggregateRoot
{
    use EventRecording;

    public function __construct(
        private readonly UserId $id,
        private UserName $name,
        private UserEmail $email,
        private UserPassword $password,
        private Roles $roles,
        private readonly CreatedAt $createdAt,
        private ?UpdatedAt $updatedAt,
    ) {
    }

    public static function create(
        UserId $userId,
        UserName $name,
        UserEmail $email,
        UserPassword $password,
        Roles $roles,
    ): self {
        $user = new self($userId, $name, $email, $password, $roles, CreatedAt::now(), null);
        $user->recordThat(
            new UserWasCreated(
                $userId->asString(),
                $user->createdAt->asString(),
                $name->asString(),
                $email->asString(),
                $password->asString(),
                $roles->toArray(),
            )
        );
        return $user;
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function email(): UserEmail
    {
        return $this->email;
    }

    public function roles(): Roles
    {
        return $this->roles;
    }

    public function password(): UserPassword
    {
        return $this->password;
    }

    #[Pure]
    public function isGranted(Role $role): bool
    {
        return $this->roles->contains($role);
    }

    public function changeEmail(UserEmail $newEmail): void
    {
        if ($this->email->isEqualTo($newEmail)) {
            return;
        }

        $this->email = $newEmail;
        $this->updatedAt = UpdatedAt::now();

        $this->recordThat(
            new UserEmailWasChanged($this->id->asString(), $this->updatedAt->asString(), $newEmail->asString())
        );
    }

    public function changeName(UserName $newName): void
    {
        if ($this->name->isEqualTo($newName)) {
            return;
        }

        $this->name = $newName;
        $this->updatedAt = UpdatedAt::now();

        $this->recordThat(
            new UserNameWasChanged($this->id->asString(), $this->updatedAt->asString(), $newName->asString())
        );
    }

    public function changePassword(UserPassword $newPassword): void
    {
        if ($this->password->isEqualTo($newPassword)) {
            return;
        }

        $this->password = $newPassword;
        $this->updatedAt = UpdatedAt::now();

        $this->recordThat(
            new UserPasswordWasChanged($this->id->asString(), $this->updatedAt->asString(), $newPassword->asString())
        );
    }

    public function changeRoles(Roles $newRoles): void
    {
        if ($this->roles->isEqualTo($newRoles)) {
            return;
        }

        $this->roles = $newRoles;
        $this->updatedAt = UpdatedAt::now();

        $this->recordThat(
            new UserRolesWasChanged($this->id->asString(), $this->updatedAt->asString(), $newRoles->toArray())
        );
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return \sprintf(
            'User [id => %s, name => %s, email => %s, roles => %s, createdAt => %s, updatedAt => %s]',
            $this->id,
            $this->name,
            $this->email,
            $this->roles,
            $this->createdAt,
            $this->updatedAt?->asString() ?? '-',
        );
    }
}
