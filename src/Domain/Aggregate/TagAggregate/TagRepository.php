<?php

declare(strict_types=1);

namespace FC\Domain\Aggregate\TagAggregate;

use FC\Domain\Exception\NotFoundException;

interface TagRepository
{
    /**
     * @throws NotFoundException
     */
    public function get(TagId $id): Tag;

    public function save(Tag $tag): void;

    public function remove(Tag $tag): void;
}
