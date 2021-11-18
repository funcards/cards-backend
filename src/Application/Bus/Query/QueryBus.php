<?php

declare(strict_types=1);

namespace FC\Application\Bus\Query;

use FC\Application\Bus\Query\Exception\QueryBusException;

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
