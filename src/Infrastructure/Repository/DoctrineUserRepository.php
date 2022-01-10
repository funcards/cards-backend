<?php

declare(strict_types=1);

namespace FC\Infrastructure\Repository;

use FC\Domain\Aggregate\UserAggregate\User;
use FC\Domain\Aggregate\UserAggregate\UserEmail;
use FC\Domain\Aggregate\UserAggregate\UserId;
use FC\Domain\Aggregate\UserAggregate\UserRepository;
use FC\Domain\Exception\NotFoundException;

final class DoctrineUserRepository extends DoctrineRepository implements UserRepository
{
    /**
     * {@inheritDoc}
     */
    public function get(UserId|UserEmail $key): User
    {
        if ($key instanceof UserEmail) {
            return $this->findByEmail($key);
        }

        return $this->find(User::class, $key);
    }

    public function save(User $user): void
    {
        $this->persist($user);
    }

    private function findByEmail(UserEmail $email): User
    {
        $finder = $this->entityManager->getRepository(User::class);

        if (null === $user = $finder->findOneBy(['email.value' => $email->asString()])) {
            throw new NotFoundException(\sprintf('User "%s" not found.', $email));
        }

        return $user;
    }
}
