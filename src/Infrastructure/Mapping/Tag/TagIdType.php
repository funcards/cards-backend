<?php

declare(strict_types=1);

namespace FC\Infrastructure\Mapping\Tag;

use FC\Domain\Aggregate\TagAggregate\TagId;
use FC\Infrastructure\Mapping\IdType;

final class TagIdType extends IdType
{
    /**
     * {@inheritDoc}
     */
    protected function getIdClass(): string
    {
        return TagId::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'tag_id';
    }
}
