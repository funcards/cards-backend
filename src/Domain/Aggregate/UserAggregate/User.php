<?php

declare(strict_types=1);

namespace FC\Domain\Aggregate\UserAggregate;

use Assert\Assert;
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

    /**
     * @param UserId $id
     * @param UserName $name
     * @param UserEmail $email
     * @param UserPassword $password
     * @param Roles $roles
     * @param CreatedAt $createdAt
     * @param UpdatedAt|null $updatedAt
     */
    public function __construct(
        private UserId $id,
        private UserName $name,
        private UserEmail $email,
        private UserPassword $password,
        private Roles $roles,
        private CreatedAt $createdAt,
        private ?UpdatedAt $updatedAt,
    ) {
    }

    /**
     * @param UserId $userId
     * @param UserName $name
     * @param UserEmail $email
     * @param UserPassword $password
     * @param Roles $roles
     * @return static
     */
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

    /**
     * @return UserId
     */
    public function id(): UserId
    {
        return $this->id;
    }

    /**
     * @return UserEmail
     */
    public function email(): UserEmail
    {
        return $this->email;
    }

    /**
     * @return Roles
     */
    public function roles(): Roles
    {
        return $this->roles;
    }

    /**
     * @return UserPassword
     */
    public function password(): UserPassword
    {
        return $this->password;
    }

    /**
     * @param Role $role
     * @return bool
     */
    #[Pure]
    public function isGranted(Role $role): bool
    {
        return $this->roles->contains($role);
    }

    /**
     * @param UserEmail $newEmail
     */
    public function changeEmail(UserEmail $newEmail): void
    {
        Assert::that($newEmail->asString())->notEq($this->email->asString());

        $this->email = $newEmail;
        $this->updatedAt = UpdatedAt::now();

        $this->recordThat(
            new UserEmailWasChanged($this->id->asString(), $this->updatedAt->asString(), $newEmail->asString())
        );
    }

    /**
     * @param UserName $newName
     */
    public function changeName(UserName $newName): void
    {
        Assert::that($newName->asString())->notEq($this->name->asString());

        $this->name = $newName;
        $this->updatedAt = UpdatedAt::now();

        $this->recordThat(
            new UserNameWasChanged($this->id->asString(), $this->updatedAt->asString(), $newName->asString())
        );
    }

    /**
     * @param UserPassword $newPassword
     */
    public function changePassword(UserPassword $newPassword): void
    {
        Assert::that($newPassword->asString())->notEq($this->password->asString());

        $this->password = $newPassword;
        $this->updatedAt = UpdatedAt::now();

        $this->recordThat(
            new UserPasswordWasChanged($this->id->asString(), $this->updatedAt->asString(), $newPassword->asString())
        );
    }

    /**
     * @param Roles $newRoles
     */
    public function changeRoles(Roles $newRoles): void
    {
        Assert::that($newRoles->toArray())->notEq($this->roles->toArray());

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
