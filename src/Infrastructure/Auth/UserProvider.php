<?php

declare(strict_types=1);

namespace FC\Infrastructure\Auth;

use Doctrine\DBAL\Connection;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class UserProvider implements UserProviderInterface
{
    /**
     * @param Connection $connection
     */
    public function __construct(private Connection $connection)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(\sprintf('Instances of "%s" are not supported.', \get_debug_type($user)));
        }

        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }

    /**
     * {@inheritDoc}
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $data = $this->connection
            ->createQueryBuilder()
            ->select('u.id', 'u.password', 'u.roles')
            ->from('users', 'u')
            ->where('u.id = ?')
            ->setParameter(0, $identifier)
            ->setMaxResults(1)
            ->fetchAssociative();

        if (!$data) {
            $ex = new UserNotFoundException(\sprintf('User "%s" does not exist.', $identifier));
            $ex->setUserIdentifier($identifier);

            throw $ex;
        }

        return new User($data['id'], $data['password'], \json_decode($data['roles'], true));
    }
}
