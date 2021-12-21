<?php

declare(strict_types=1);

namespace FC\Domain\Aggregate\UserAggregate;

use FC\Domain\Exception\NotFoundException;

interface UserRepository
{
    /**
     * @throws NotFoundException
     */
    public function get(UserId|UserEmail $key): User;

    public function save(User $user): void;
}
