<?php

declare(strict_types=1);

namespace FC\Application\Bus\Query;

use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[OA\Schema(schema: 'PaginatedResponse', required: ['page_index', 'page_size', 'count'])]
class PaginatedResponse implements Response
{
    /**
     * @param array<Response> $data
     */
    public function __construct(
        #[OA\Property('page_index'), SerializedName('page_index')] public readonly int $pageIndex,
        #[OA\Property('page_size'), SerializedName('page_size')] public readonly int $pageSize,
        #[OA\Property] public readonly int $count,
        public readonly array $data,
    ) {
    }
}
