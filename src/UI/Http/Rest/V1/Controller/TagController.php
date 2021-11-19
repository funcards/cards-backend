<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest\V1\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Tags",
 *     description="Tags API"
 * )
 * @OA\Schema(schema="tagId", type="string", format="uuid")
 */
#[Route('/api/v1/boards/{boardId}/tags', requirements: ['boardId' => self::UUID_REGEX])]
final class TagController extends ApiController
{
    #[Route('/{tagId}', 'tag', ['tagId' => self::UUID_REGEX], methods: 'GET')]
    public function get(string $boardId, string $tagId): Response
    {
        $this->debugMethod(__METHOD__);

        return new Response();
    }
}
