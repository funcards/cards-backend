<?php

declare(strict_types=1);

namespace FC\Tag\Domain;

use FC\Shared\Domain\Exception\NotFoundDomainException;
use FC\Shared\Domain\ValueObject\Tag\TagId;

interface TagRepository
{
    /**
     * @param TagId $id
     * @return Tag
     * @throws NotFoundDomainException
     */
    public function get(TagId $id): Tag;

    /**
     * @param Tag $tag
     */
    public function save(Tag $tag): void;
}
