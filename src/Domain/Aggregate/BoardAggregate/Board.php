<?php

declare(strict_types=1);

namespace FC\Domain\Aggregate\BoardAggregate;

use Assert\Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FC\Domain\Aggregate\AggregateRoot;
use FC\Domain\Aggregate\EventRecording;
use FC\Domain\Aggregate\UserAggregate\UserId;
use FC\Domain\Event\Board\BoardColorWasChanged;
use FC\Domain\Event\Board\BoardDescriptionWasChanged;
use FC\Domain\Event\Board\BoardMemberWasAdded;
use FC\Domain\Event\Board\BoardMemberWasChanged;
use FC\Domain\Event\Board\BoardMemberWasRemoved;
use FC\Domain\Event\Board\BoardNameWasChanged;
use FC\Domain\Event\Board\BoardWasCreated;
use FC\Domain\Exception\NotFoundException;
use FC\Domain\ValueObject\CreatedAt;
use FC\Domain\ValueObject\Role;
use FC\Domain\ValueObject\Roles;
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
     * @param BoardId $boardId
     * @param UserId $ownerId
     * @param BoardName $name
     * @param BoardColor $color
     * @param BoardDescription $description
     * @return static
     */
    public static function create(
        BoardId $boardId,
        UserId $ownerId,
        BoardName $name,
        BoardColor $color,
        BoardDescription $description,
    ): self {
        $board = new self($boardId, $ownerId, $name, $color, $description, CreatedAt::now(), new ArrayCollection());
        $board->recordThat(
            new BoardWasCreated(
                $boardId->asString(),
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
        } catch (NotFoundException) {
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
     * @throws NotFoundException
     */
    public function member(UserId $userId): Member
    {
        foreach ($this->members as $member) {
            if ($member->userId()->equals($userId)) {
                return $member;
            }
        }

        throw new NotFoundException(
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
        } catch (NotFoundException) {
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
        $this->members->removeElement($this->member($userId));
        $this->recordThat(new BoardMemberWasRemoved($this->id->asString(), $userId->asString()));
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
