<?php

declare(strict_types=1);

namespace FC\Domain\Aggregate\CardAggregate;

use Assert\Assert;
use FC\Domain\Aggregate\TagAggregate\TagId;
use JetBrains\PhpStorm\Pure;

final class CardTags implements \Countable, \Stringable
{
    /**
     * @var array<string>
     */
    private array $tags;

    /**
     * @param array<TagId|string> $tags
     */
    public function __construct(array $tags)
    {
        $this->tags = \array_unique(\array_map(static fn($tag) => (string)$tag, $tags));

        Assert::thatAll($this->tags)->uuid();
    }

    public static function from(TagId|string ...$tags): self
    {
        return new self($tags);
    }

    public function add(TagId|string $tagId): self
    {
        $tagId = (string)$tagId;

        Assert::thatAll($tagId)->uuid();
        Assert::that($this->tags)->notContains($tagId);

        $cloned = clone $this;
        $cloned->tags[] = $tagId;

        return $cloned;
    }

    public function remove(TagId|string $tagId): self
    {
        $tagId = (string)$tagId;

        Assert::thatAll($tagId)->uuid();
        Assert::that($this->tags)->contains($tagId);

        $cloned = clone $this;
        \array_splice($cloned->tags, \array_search($tagId, $cloned->tags, true), 1);

        return $cloned;
    }

    /**
     * @return array<string>
     */
    public function toArray(): array
    {
        return $this->tags;
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
        return \count($this->tags);
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return \json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }

    #[Pure]
    public function contains(TagId $tagId): bool
    {
        return \in_array($tagId->asString(), $this->toArray(), true);
    }

    #[Pure]
    public function isEqualTo(self $tags): bool
    {
        return \count($this->tags) === \count($tags->toArray())
            && \count($this->tags) === \count(\array_intersect($this->tags, $tags->toArray()));
    }
}
