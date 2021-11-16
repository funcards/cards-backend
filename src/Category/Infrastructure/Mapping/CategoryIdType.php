<?php

declare(strict_types=1);

namespace FC\Category\Infrastructure\Mapping;

use FC\Shared\Domain\ValueObject\Category\CategoryId;
use FC\Shared\Infrastructure\Mapping\IdType;

final class CategoryIdType extends IdType
{
    /**
     * {@inheritDoc}
     */
    protected function getIdClass(): string
    {
        return CategoryId::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'category_id';
    }
}
