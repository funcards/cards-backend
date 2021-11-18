<?php

declare(strict_types=1);

namespace FC\Domain\Aggregate\TagAggregate;

use FC\Domain\Exception\NotFoundException;

interface TagRepository
{
    /**
     * @param TagId $id
     * @return Tag
     * @throws NotFoundException
     */
    public function get(TagId $id): Tag;

    /**
     * @param Tag $tag
     */
    public function save(Tag $tag): void;
}
