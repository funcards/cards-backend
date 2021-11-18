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
    private string $eventId;

    /**
     * @var string
     */
    private string $occurredOn;

    /**
     * @param string $aggregateId
     * @param string|null $occurredOn
     */
    public function __construct(private string $aggregateId, ?string $occurredOn = null)
    {
        Assert::that($aggregateId)->uuid();
        Assert::thatNullOr($occurredOn)->notEmpty();

        $this->eventId = Uuid::v4()->toRfc4122();
        $this->occurredOn = $occurredOn ?? self::now();
    }

    /**
     * @return string
     */
    public static function eventName(): string
    {
        return static::class;
    }

    /**
     * @return string
     */
    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    /**
     * @return string
     */
    public function eventId(): string
    {
        return $this->eventId;
    }

    /**
     * @return string
     */
    public function occurredOn(): string
    {
        return $this->occurredOn;
    }

    /**
     * @return string
     */
    private static function now(): string
    {
        return (new \DateTimeImmutable('now', new \DateTimeZone('UTC')))->format(self::DATE_TIME_FORMAT);
    }
}
