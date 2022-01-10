<?php

declare(strict_types=1);

namespace FC\Domain\ValueObject;

use JetBrains\PhpStorm\Pure;

final class Date implements \Stringable
{
    private const DATE_FORMAT = 'Y-m-d';

    public function __construct(private readonly \DateTimeImmutable $dateTime)
    {
    }

    public static function now(): self
    {
        return new self(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
    }

    public static function fromString(string $date): self
    {
        $dt = \DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $date);

        if (!$dt instanceof \DateTimeImmutable) {
            throw new \InvalidArgumentException(
                \sprintf(
                    'Invalid date string provided: %s. Expected format: %s',
                    $date,
                    self::DATE_FORMAT
                )
            );
        }

        return new self($dt);
    }

    #[Pure]
    public static function fromDateTimeImmutable(\DateTimeImmutable $dateTime): self
    {
        return new self($dateTime);
    }

    /**
     * {@inheritDoc}
     */
    final public function __toString(): string
    {
        return $this->asString();
    }

    public function asString(): string
    {
        return $this->dateTime->format(self::DATE_FORMAT);
    }

    public function asDateTimeImmutable(): \DateTimeImmutable
    {
        return $this->dateTime;
    }

    public function year(): int
    {
        return (int)$this->dateTime->format('Y');
    }

    public function month(): int
    {
        return (int)$this->dateTime->format('m');
    }

    public function day(): int
    {
        return (int)$this->dateTime->format('d');
    }
}
