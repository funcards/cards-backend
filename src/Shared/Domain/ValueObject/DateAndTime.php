<?php

declare(strict_types=1);

namespace FC\Shared\Domain\ValueObject;

use JetBrains\PhpStorm\Pure;

abstract class DateAndTime implements \Stringable
{
    public const DATE_TIME_FORMAT = 'Y-m-d\TH:i';

    final public function __construct(protected \DateTimeImmutable $dateTime)
    {
    }

    /**
     * @return static
     */
    final public static function now(): static
    {
        return new static(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
    }

    /**
     * @param string $dateTime
     * @return DateAndTime
     */
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

    /**
     * @return string
     */
    final public function asString(): string
    {
        return $this->dateTime->format(self::DATE_TIME_FORMAT);
    }

    /**
     * @return Date
     */
    #[Pure]
    final public function asDate(): Date
    {
        return Date::fromDateTimeImmutable($this->dateTime);
    }

    /**
     * @param \DateTimeImmutable $now
     * @return bool
     */
    final public function isInTheFuture(\DateTimeImmutable $now): bool
    {
        return $now < $this->dateTime;
    }

    /**
     * @return \DateTimeImmutable
     */
    final public function toDateTimeImmutable(): \DateTimeImmutable
    {
        return $this->dateTime;
    }

    /**
     * @return int
     */
    final public function year(): int
    {
        return (int)$this->dateTime->format('Y');
    }

    /**
     * @return int
     */
    final public function month(): int
    {
        return (int)$this->dateTime->format('m');
    }

    /**
     * @return int
     */
    final public function day(): int
    {
        return (int)$this->dateTime->format('d');
    }
}
