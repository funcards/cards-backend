<?php

declare(strict_types=1);

namespace FC\Tag\Infrastructure\Repository;

use FC\Shared\Domain\ValueObject\Tag\TagId;
use FC\Shared\Infrastructure\Repository\DoctrineRepository;
use FC\Tag\Domain\Tag;
use FC\Tag\Domain\TagRepository;

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
}
