<?php

declare(strict_types=1);

namespace FC\Application\Bus\Query\Exception;

use FC\Application\Bus\Query\Query;
use JetBrains\PhpStorm\Pure;

final class QueryHandlerNotFoundException extends \RuntimeException implements QueryBusException
{
    /**
     * @param Query $query
     * @param \Throwable|null $previous
     * @return static
     */
    #[Pure]
    public static function create(Query $query, ?\Throwable $previous = null): self
    {
        return new self(\sprintf('Handler for query "%s" not found.', $query::class), 0, $previous);
    }
}
