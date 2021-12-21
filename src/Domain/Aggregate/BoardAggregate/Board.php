<?php

declare(strict_types=1);

namespace FC\Domain\Aggregate\BoardAggregate;

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

    private const DEFAULT_ROLES = [
        Role::BOARD_VIEW,
        Role::CATEGORY_VIEW,
        Role::TAG_VIEW,
        Role::CARD_VIEW,
    ];

    /**
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

    public function id(): BoardId
    {
        return $this->id;
    }

    public function ownerId(): UserId
    {
        return $this->ownerId;
    }

    #[Pure]
    public function isOwner(UserId $id): bool
    {
        return $this->ownerId->isEqualTo($id);
    }

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
     * @throws NotFoundException
     */
    public function member(UserId $userId): Member
    {
        foreach ($this->members as $member) {
            if ($member->userId()->isEqualTo($userId)) {
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

    public function addMember(UserId $userId, Roles $roles): void
    {
        if ($roles->contains(Role::boardOwner())) {
            if (!$this->ownerId->isEqualTo($userId)) {
                return;
            }
        } else {
            $roles = $roles->add(...self::DEFAULT_ROLES);
        }

        try {
            $member = $this->member($userId);

            if ($member->roles()->isEqualTo($roles)) {
                return;
            }

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

    public function removeMember(UserId $userId): void
    {
        if ($this->isOwner($userId)) {
            return;
        }

        $this->members->removeElement($this->member($userId));
        $this->recordThat(new BoardMemberWasRemoved($this->id->asString(), $userId->asString()));
    }

    public function changeName(BoardName $newName): void
    {
        if ($this->name->isEqualTo($newName)) {
            return;
        }

        $this->name = $newName;

        $this->recordThat(new BoardNameWasChanged($this->id->asString(), $newName->asString()));
    }

    public function changeColor(BoardColor $newColor): void
    {
        if ($this->color->isEqualTo($newColor)) {
            return;
        }

        $this->color = $newColor;

        $this->recordThat(new BoardColorWasChanged($this->id->asString(), $newColor->asString()));
    }

    public function changeDescription(BoardDescription $newDescription): void
    {
        if ($this->description->isEqualTo($newDescription)) {
            return;
        }

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
