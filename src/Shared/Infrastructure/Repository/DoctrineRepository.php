<?php

declare(strict_types=1);

namespace FC\Shared\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use FC\Shared\Domain\Aggregate\AggregateRoot;
use FC\Shared\Domain\Exception\NotFoundDomainException;
use FC\Shared\Domain\ValueObject\Id;

abstract class DoctrineRepository
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(protected EntityManagerInterface $entityManager)
    {
    }

    /**
     * @template T of AggregateRoot
     *
     * @param string $className
     * @param Id $id
     * @psalm-param class-string<T> $className
     *
     * @return AggregateRoot
     * @psalm-return T
     *
     * @throws NotFoundDomainException
     */
    protected function find(string $className, Id $id): AggregateRoot
    {
        if (null === $entity = $this->entityManager->find($className, $id)) {
            throw NotFoundDomainException::create($id);
        }

        return $entity;
    }

    /**
     * @param AggregateRoot $entity
     */
    protected function persist(AggregateRoot $entity): void
    {
        $this->entityManager->persist($entity);
    }
}
