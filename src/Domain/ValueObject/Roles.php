<?php

declare(strict_types=1);

namespace FC\Domain\ValueObject;

use JetBrains\PhpStorm\Pure;

final class Roles implements \Countable, \Stringable
{
    /**
     * @var array<Role>
     */
    private readonly array $items;

    /**
     * @param array<Role|string> $roles
     */
    public function __construct(array $roles)
    {
        $this->items = \array_unique(
            \array_map(static fn($role) => $role instanceof Role ? $role : Role::from($role), $roles),
            \SORT_REGULAR,
        );
    }

    public static function from(Role|string ...$roles): self
    {
        return new self($roles);
    }

    public function add(Role|string ...$roles): self
    {
        return new self(
            \array_merge(
                $this->items,
                \array_map(static fn($role) => $role instanceof Role ? $role : Role::from($role), $roles)
            )
        );
    }

    /**
     * @return array<string>
     */
    public function toArray(): array
    {
        return \array_map(static fn($role) => $role->value, $this->items);
    }

    /**
     * {@inheritDoc}
     */
    final public function __toString(): string
    {
        return \json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }

    #[Pure]
    public function isEmpty(): bool
    {
        return 0 === $this->count();
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return \count($this->items);
    }


    #[Pure]
    public function contains(Role $role, Role ...$roles): bool
    {
        if (!\in_array($role, $this->items, true)) {
            return false;
        }

        foreach ($roles as $r) {
            if (!\in_array($r, $this->items, true)) {
                return false;
            }
        }

        return true;
    }

    #[Pure]
    public function isEqualTo(self $roles): bool
    {
        return \count($this->items) === \count($roles->items)
            && \count($this->items) === \count(\array_intersect($this->items, $roles->items));
    }
}
