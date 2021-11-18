<?php

declare(strict_types=1);

namespace FC\Domain\Aggregate\UserAggregate;

use FC\Domain\Exception\NotFoundException;

interface UserRepository
{
    /**
     * @param UserId|UserEmail $key
     * @return User
     * @throws NotFoundException
     */
    public function get(UserId|UserEmail $key): User;

    /**
     * @param User $user
     */
    public function save(User $user): void;
}
