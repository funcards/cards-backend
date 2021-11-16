<?php

declare(strict_types=1);

namespace FC\Shared\Domain\ValueObject;

use Assert\Assert;
use JetBrains\PhpStorm\Pure;

final class Roles implements \Countable, \Stringable
{
    /**
     * @param array<string> $roles
     */
    public function __construct(private array $roles)
    {
        Assert::thatAll($roles)->startsWith('ROLE_');
    }

    /**
     * @param string ...$roles
     * @return static
     */
    public static function from(string ...$roles): self
    {
        return new self($roles);
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
        return \sprintf('[%s]', \implode(', ', $this->roles));
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
}
