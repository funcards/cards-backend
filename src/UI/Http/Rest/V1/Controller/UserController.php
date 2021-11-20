<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest\V1\Controller;

use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="Users API"
 * )
 */
#[Route('/api/v1/users')]
final class UserController extends ApiController
{
}
