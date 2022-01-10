<?php

declare(strict_types=1);

namespace FC\Domain\Event;

use Assert\Assert;
use Symfony\Component\Uid\Uuid;

abstract class DomainEvent
{
    private const DATE_TIME_FORMAT = 'Y-m-d\TH:i';

    /**
     * @var string
     */
    public readonly string $eventId;

    /**
     * @var string
     */
    public readonly string $occurredOn;

    public function __construct(public readonly string $aggregateId, ?string $occurredOn = null)
    {
        Assert::that($aggregateId)->uuid();
        Assert::thatNullOr($occurredOn)->notEmpty();

        $this->eventId = Uuid::v4()->toRfc4122();
        $this->occurredOn = $occurredOn ?? self::now();
    }

    public static function eventName(): string
    {
        return static::class;
    }

    private static function now(): string
    {
        return (new \DateTimeImmutable('now', new \DateTimeZone('UTC')))->format(self::DATE_TIME_FORMAT);
    }
}
