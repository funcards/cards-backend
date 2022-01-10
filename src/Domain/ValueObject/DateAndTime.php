<?php

declare(strict_types=1);

namespace FC\Domain\ValueObject;

use JetBrains\PhpStorm\Pure;

abstract class DateAndTime implements \Stringable
{
    public final const DATE_TIME_FORMAT = 'Y-m-d\TH:i';

    final public function __construct(protected \DateTimeImmutable $dateTime)
    {
    }

    final public static function now(): static
    {
        return new static(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
    }

    final public static function fromString(string $dateTime): self
    {
        $dt = \DateTimeImmutable::createFromFormat(self::DATE_TIME_FORMAT, $dateTime);
        if (!$dt instanceof \DateTimeImmutable) {
            throw new \InvalidArgumentException(
                \sprintf(
                    'Invalid date/time format. Provided: "%s", expected format: "%s"',
                    $dateTime,
                    self::DATE_TIME_FORMAT
                ),
            );
        }

        return new static($dt);
    }

    /**
     * {@inheritDoc}
     */
    final public function __toString(): string
    {
        return $this->asString();
    }

    final public function asString(): string
    {
        return $this->dateTime->format(self::DATE_TIME_FORMAT);
    }

    #[Pure]
    final public function asDate(): Date
    {
        return Date::fromDateTimeImmutable($this->dateTime);
    }

    final public function isInTheFuture(\DateTimeImmutable $now): bool
    {
        return $now < $this->dateTime;
    }

    final public function toDateTimeImmutable(): \DateTimeImmutable
    {
        return $this->dateTime;
    }

    final public function year(): int
    {
        return (int)$this->dateTime->format('Y');
    }

    final public function month(): int
    {
        return (int)$this->dateTime->format('m');
    }

    final public function day(): int
    {
        return (int)$this->dateTime->format('d');
    }
}
