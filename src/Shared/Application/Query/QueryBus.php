<?php

declare(strict_types=1);

namespace FC\Shared\Application\Query;

use FC\Shared\Application\Query\Exception\QueryBusException;

interface QueryBus
{
    /**
     * @param Query $query
     * @return Response
     * @throws QueryBusException
     * @throws \Throwable
     */
    public function ask(Query $query): Response;
}
