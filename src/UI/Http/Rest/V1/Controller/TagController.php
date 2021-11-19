<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest\V1\Controller;

use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Tags",
 *     description="Tags API"
 * )
 */
#[Route('/api/v1')]
final class TagController extends ApiController
{
}
