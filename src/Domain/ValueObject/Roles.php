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
        $this->roles = \array_map(static fn($role) => (string)$role, $roles);
    }

    /**
     * @param Role ...$roles
     * @return static
     */
    public static function from(Role ...$roles): self
    {
        return new self($roles);
    }

    /**
     * @param string ...$roles
     * @return static
     */
    public static function fromString(string ...$roles): self
    {
        return self::from(...\array_map(static fn($role) => Role::fromString($role), $roles));
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
        return \json_encode($this->toArray());
    }

    /**
     * @return bool
     */
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


    /**
     * @param Role $role
     * @return bool
     */
    #[Pure]
    public function contains(Role $role): bool
    {
        return \in_array($role->asString(), $this->toArray(), true);
    }

    /**
     * @param Roles $roles
     * @return bool
     */
    #[Pure]
    public function isEqualTo(self $roles): bool
    {
        return \count($this->roles) === \count($roles->toArray())
            && \count($this->roles) === \count(\array_intersect($this->roles, $roles->toArray()));
    }
}
