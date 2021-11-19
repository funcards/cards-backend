<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest\V1\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Cards",
 *     description="Cards API"
 * )
 */
#[Route('/api/v1/boards/{boardId}/cards', requirements: ['boardId' => self::UUID_REGEX])]
final class CardController extends ApiController
{
    #[Route('/{cardId}', 'card', ['cardId' => self::UUID_REGEX], methods: 'GET')]
    public function get(string $boardId, string $cardId): Response
    {
        $this->debugMethod(__METHOD__);

        return new Response();
    }
}
