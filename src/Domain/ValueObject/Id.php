<?php

declare(strict_types=1);

namespace FC\Domain\ValueObject;

use Assert\Assert;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Uid\NilUuid;
use Symfony\Component\Uid\Uuid;

abstract class Id implements \Stringable
{
    /**
     * @param Uuid $id
     */
    final private function __construct(private Uuid $id)
    {
        Assert::that($id)->notIsInstanceOf(NilUuid::class);
    }

    /**
     * @return static
     */
    final public static function random(): static
    {
        return new static(Uuid::v4());
    }

    /**
     * @param string $id
     * @return static
     */
    final public static function fromString(string $id): static
    {
        Assert::that($id)->uuid();

        return new static(Uuid::fromString($id));
    }

    /**
     * @return string
     */
    #[Pure]
    final public function __toString(): string
    {
        return $this->asString();
    }

    /**
     * @return string
     */
    #[Pure]
    final public function asString(): string
    {
        return $this->id->toRfc4122();
    }

    /**
     * @return Uuid
     */
    final public function toUuid(): Uuid
    {
        return $this->id;
    }

    /**
     * @param object|null $other
     * @return bool
     */
    final public function isEqualTo(?object $other): bool
    {
        return \get_class($other) === \get_class($this) && (string)$this === (string)$other;
    }
}
