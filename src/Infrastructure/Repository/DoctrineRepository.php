<?php

declare(strict_types=1);

namespace FC\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use FC\Domain\Aggregate\AggregateRoot;
use FC\Domain\Exception\NotFoundException;
use FC\Domain\ValueObject\Id;

abstract class DoctrineRepository
{
    public function __construct(protected EntityManagerInterface $entityManager)
    {
    }

    /**
     * @template T of AggregateRoot
     *
     * @psalm-param class-string<T> $className
     *
     * @throws NotFoundException
     */
    protected function find(string $className, Id $id): AggregateRoot
    {
        if (null === $entity = $this->entityManager->find($className, $id)) {
            throw NotFoundException::new($id);
        }

        return $entity;
    }

    protected function persist(AggregateRoot $entity): void
    {
        $this->entityManager->persist($entity);
    }

    protected function delete(AggregateRoot $entity): void
    {
        $this->entityManager->remove($entity);
    }
}
