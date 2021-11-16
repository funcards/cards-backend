<?php

declare(strict_types=1);

namespace FC\User\Infrastructure\Repository;

use FC\Shared\Domain\Exception\NotFoundDomainException;
use FC\Shared\Domain\ValueObject\User\UserId;
use FC\Shared\Infrastructure\Repository\DoctrineRepository;
use FC\User\Domain\User;
use FC\User\Domain\UserEmail;
use FC\User\Domain\UserRepository;

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

    /**
     * {@inheritDoc}
     */
    public function save(User $user): void
    {
        $this->persist($user);
    }

    /**
     * @param UserEmail $email
     * @return User
     */
    private function findByEmail(UserEmail $email): User
    {
        $finder = $this->entityManager->getRepository(User::class);

        if (null === $user = $finder->findOneBy(['email.value' => $email->asString()])) {
            throw new NotFoundDomainException(\sprintf('User "%s" not found.', $email));
        }

        return $user;
    }
}
