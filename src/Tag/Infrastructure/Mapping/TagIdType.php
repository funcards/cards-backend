<?php

declare(strict_types=1);

namespace FC\Tag\Infrastructure\Mapping;

use FC\Shared\Domain\ValueObject\Tag\TagId;
use FC\Shared\Infrastructure\Mapping\IdType;

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
