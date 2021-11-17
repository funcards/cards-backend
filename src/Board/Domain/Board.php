<?php

declare(strict_types=1);

namespace FC\Board\Domain;

use Assert\Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FC\Board\Domain\Event\BoardColorWasChanged;
use FC\Board\Domain\Event\BoardDescriptionWasChanged;
use FC\Board\Domain\Event\BoardMemberWasAdded;
use FC\Board\Domain\Event\BoardMemberWasChanged;
use FC\Board\Domain\Event\BoardMemberWasRemoved;
use FC\Board\Domain\Event\BoardNameWasChanged;
use FC\Board\Domain\Event\BoardWasCreated;
use FC\Board\Domain\Exception\MemberNotFoundException;
use FC\Shared\Domain\Aggregate\AggregateRoot;
use FC\Shared\Domain\Aggregate\EventRecording;
use FC\Shared\Domain\ValueObject\Board\BoardId;
use FC\Shared\Domain\ValueObject\Board\MemberId;
use FC\Shared\Domain\ValueObject\CreatedAt;
use FC\Shared\Domain\ValueObject\Role;
use FC\Shared\Domain\ValueObject\Roles;
use FC\Shared\Domain\ValueObject\User\UserId;
use JetBrains\PhpStorm\Pure;

final class Board implements AggregateRoot
{
    use EventRecording;

    /**
     * @param BoardId $id
     * @param UserId $ownerId
     * @param BoardName $name
     * @param BoardColor $color
     * @param BoardDescription $description
     * @param CreatedAt $createdAt
     * @param Collection<int, Member> $members
     */
    public function __construct(
        private BoardId $id,
        private UserId $ownerId,
        private BoardName $name,
        private BoardColor $color,
        private BoardDescription $description,
        private CreatedAt $createdAt,
        private Collection $members,
    ) {
    }

    /**
     * @param BoardId $id
     * @param UserId $ownerId
     * @param BoardName $name
     * @param BoardColor $color
     * @param BoardDescription $description
     * @return static
     */
    public static function create(
        BoardId $id,
        UserId $ownerId,
        BoardName $name,
        BoardColor $color,
        BoardDescription $description,
    ): self {
        $board = new self($id, $ownerId, $name, $color, $description, CreatedAt::now(), new ArrayCollection());
        $board->recordThat(
            new BoardWasCreated(
                $id->asString(),
                $board->createdAt->asString(),
                $ownerId->asString(),
                $name->asString(),
                $color->asString(),
                $description->asString(),
            )
        );
        $board->addMember($ownerId, Roles::from(Role::boardOwner()));
        return $board;
    }

    /**
     * @return BoardId
     */
    public function id(): BoardId
    {
        return $this->id;
    }

    /**
     * @return UserId
     */
    public function ownerId(): UserId
    {
        return $this->ownerId;
    }

    /**
     * @param UserId $id
     * @return bool
     */
    #[Pure]
    public function isOwner(UserId $id): bool
    {
        return $this->ownerId->equals($id);
    }

    /**
     * @param UserId $userId
     * @param Role $role
     * @return bool
     */
    public function isGranted(UserId $userId, Role $role): bool
    {
        if ($this->isOwner($userId)) {
            return true;
        }

        try {
            return $this->member($userId)->roles()->contains($role);
        } catch (MemberNotFoundException) {
            return false;
        }
    }

    /**
     * @return array<Member>
     */
    public function members(): array
    {
        return $this->members->getValues();
    }

    /**
     * @param UserId $userId
     * @return Member
     * @throws MemberNotFoundException
     */
    public function member(UserId $userId): Member
    {
        foreach ($this->members as $member) {
            if ($member->userId()->equals($userId)) {
                return $member;
            }
        }

        throw new MemberNotFoundException(
            \sprintf(
                'Member (user) %s for board %s not found.',
                $userId->asString(),
                $this->id->asString(),
            )
        );
    }

    /**
     * @param UserId $userId
     * @param Roles $roles
     */
    public function addMember(UserId $userId, Roles $roles): void
    {
        try {
            $member = $this->member($userId);
            $member->changeRoles($roles);
            $this->recordThat(
                new BoardMemberWasChanged(
                    $this->id->asString(),
                    $userId->asString(),
                    $roles->toArray(),
                )
            );
        } catch (MemberNotFoundException) {
            $this->members[] = new Member(MemberId::random(), $userId, $roles, CreatedAt::now(), $this);
            $this->recordThat(
                new BoardMemberWasAdded($this->id->asString(), $userId->asString(), $roles->toArray())
            );
        }
    }

    /**
     * @param UserId $userId
     */
    public function removeMember(UserId $userId): void
    {
        foreach ($this->members as $index => $member) {
            if ($member->userId()->equals($userId)) {
                unset($this->members[$index]);
                $this->recordThat(new BoardMemberWasRemoved($this->id->asString(), $userId->asString()));
                break;
            }
        }
    }

    /**
     * @param BoardName $newName
     */
    public function changeName(BoardName $newName): void
    {
        Assert::that($newName->asString())->notEq($this->name->asString());

        $this->name = $newName;

        $this->recordThat(new BoardNameWasChanged($this->id->asString(), $newName->asString()));
    }

    /**
     * @param BoardColor $newColor
     */
    public function changeColor(BoardColor $newColor): void
    {
        Assert::that($newColor->asString())->notEq($this->color->asString());

        $this->color = $newColor;

        $this->recordThat(new BoardColorWasChanged($this->id->asString(), $newColor->asString()));
    }

    /**
     * @param BoardDescription $newDescription
     */
    public function changeDescription(BoardDescription $newDescription): void
    {
        Assert::that($newDescription->asString())->notEq($this->description->asString());

        $this->description = $newDescription;

        $this->recordThat(new BoardDescriptionWasChanged($this->id->asString(), $newDescription->asString()));
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return \sprintf(
            'Board [id => %s, name => %s, color => %s, ownerId => %s, createdAt => %s]',
            $this->id,
            $this->name,
            $this->color,
            $this->ownerId,
            $this->createdAt,
        );
    }
}
