<?php

declare(strict_types=1);

namespace FC\Infrastructure\Repository;

use FC\Domain\Aggregate\TagAggregate\Tag;
use FC\Domain\Aggregate\TagAggregate\TagId;
use FC\Domain\Aggregate\TagAggregate\TagRepository;

final class DoctrineTagRepository extends DoctrineRepository implements TagRepository
{
    /**
     * {@inheritDoc}
     */
    public function get(TagId $id): Tag
    {
        return $this->find(Tag::class, $id);
    }

    /**
     * {@inheritDoc}
     */
    public function save(Tag $tag): void
    {
        $this->persist($tag);
    }

    /**
     * {@inheritDoc}
     */
    public function remove(Tag $tag): void
    {
        $this->delete($tag);
    }
}
