<?php

declare(strict_types=1);

namespace FC\Application\Bus\Query;

use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @OA\Schema(schema="PaginatedResponse", required={"pageIndex", "pageSize", "count", "data"})
 */
class PaginatedResponse implements Response
{
    /**
     * @param array<Response> $data
     */
    public function __construct(
        /** @OA\Property() */ #[SerializedName('page_index')] private int $pageIndex,
        /** @OA\Property() */ #[SerializedName('page_size')] private int $pageSize,
        /** @OA\Property() */ private int $count,
        private array $data,
    ) {
    }

    public function getPageIndex(): int
    {
        return $this->pageIndex;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @return Response[]
     */
    public function getData(): array
    {
        return $this->data;
    }
}
