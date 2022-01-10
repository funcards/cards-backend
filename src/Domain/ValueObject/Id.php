<?php

declare(strict_types=1);

namespace FC\Domain\ValueObject;

use Assert\Assert;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Uid\NilUuid;
use Symfony\Component\Uid\Uuid;

abstract class Id implements \Stringable
{
    private function __construct(private readonly Uuid $id)
    {
        Assert::that($id)->notIsInstanceOf(NilUuid::class);
    }

    final public static function random(): static
    {
        return new static(Uuid::v4());
    }

    final public static function fromString(string $id): static
    {
        Assert::that($id)->uuid();

        return new static(Uuid::fromString($id));
    }

    #[Pure]
    final public function __toString(): string
    {
        return $this->asString();
    }

    #[Pure]
    final public function asString(): string
    {
        return $this->id->toRfc4122();
    }

    final public function toUuid(): Uuid
    {
        return $this->id;
    }

    final public function isEqualTo(?object $other): bool
    {
        return $other::class === $this::class && (string)$this === (string)$other;
    }
}
