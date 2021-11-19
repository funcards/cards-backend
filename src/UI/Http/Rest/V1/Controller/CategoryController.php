<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest\V1\Controller;

use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Categories",
 *     description="Categories API"
 * )
 */
#[Route('/api/v1')]
final class CategoryController extends ApiController
{
}
