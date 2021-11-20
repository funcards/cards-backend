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
     * @param int $pageIndex
     * @param int $pageSize
     * @param int $count
     * @param array<Response> $data
     */
    public function __construct(
        /** @OA\Property() */ #[SerializedName('page_index')] private int $pageIndex,
        /** @OA\Property() */ #[SerializedName('page_size')] private int $pageSize,
        /** @OA\Property() */ private int $count,
        private array $data,
    ) {
    }

    /**
     * @return int
     */
    public function getPageIndex(): int
    {
        return $this->pageIndex;
    }

    /**
     * @return int
     */
    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     * @return int
     */
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
