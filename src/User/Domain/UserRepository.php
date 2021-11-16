<?php

declare(strict_types=1);

namespace FC\User\Domain;

use FC\Shared\Domain\Exception\NotFoundDomainException;
use FC\Shared\Domain\ValueObject\User\UserId;

interface UserRepository
{
    /**
     * @param UserId|UserEmail $key
     * @return User
     * @throws NotFoundDomainException
     */
    public function get(UserId|UserEmail $key): User;

    /**
     * @param User $user
     */
    public function save(User $user): void;
}
