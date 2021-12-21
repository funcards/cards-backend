<?php

declare(strict_types=1);

namespace FC\Domain\ValueObject;

use JetBrains\PhpStorm\Pure;

final class Roles implements \Countable, \Stringable
{
    /**
     * @var array<string>
     */
    private array $roles;

    /**
     * @param array<Role|string> $roles
     */
    public function __construct(array $roles)
    {
        $this->roles = \array_unique(\array_map(static fn($role) => (string)$role, $roles));
    }

    public static function from(Role ...$roles): self
    {
        return new self($roles);
    }

    public static function fromString(string ...$roles): self
    {
        return self::from(...\array_map(static fn($role) => Role::fromString($role), $roles));
    }

    public function add(Role|string ...$roles): self
    {
        return new self(\array_merge($this->roles, \array_map(static fn($role) => (string)$role, $roles)));
    }

    /**
     * @return array<string>
     */
    public function toArray(): array
    {
        return $this->roles;
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
        return \count($this->roles);
    }


    #[Pure]
    public function contains(Role $role, Role ...$roles): bool
    {
        if (!\in_array($role->asString(), $this->toArray(), true)) {
            return false;
        }

        foreach ($roles as $r) {
            if (!\in_array($r->asString(), $this->toArray(), true)) {
                return false;
            }
        }

        return true;
    }

    #[Pure]
    public function isEqualTo(self $roles): bool
    {
        return \count($this->roles) === \count($roles->toArray())
            && \count($this->roles) === \count(\array_intersect($this->roles, $roles->toArray()));
    }
}
