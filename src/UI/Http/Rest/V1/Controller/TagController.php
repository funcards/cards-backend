<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest\V1\Controller;

use FC\Application\Bus\Command\CommandBus;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @OA\Tag(
 *     name="Tags",
 *     description="Tags API"
 * )
 */
#[Route('/api/v1')]
final class TagController
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
