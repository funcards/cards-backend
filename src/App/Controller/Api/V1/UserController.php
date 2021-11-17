<?php

declare(strict_types=1);

namespace FC\App\Controller\Api\V1;

use FC\Shared\Application\Command\CommandBus;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="Users API"
 * )
 */
#[Route('/api/v1')]
final class UserController
{
    /**
     * @param DenormalizerInterface $denormalizer
     * @param SerializerInterface $serializer
     * @param CommandBus $commandBus
     * @param LoggerInterface $logger
     */
    public function __construct(
        private DenormalizerInterface $denormalizer,
        private SerializerInterface $serializer,
        private CommandBus $commandBus,
        private LoggerInterface $logger,
    ) {
    }
}
