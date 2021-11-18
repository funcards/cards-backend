<?php

namespace FC\Domain\Aggregate;

use FC\Domain\ValueObject\Id;

interface Entity extends \Stringable
{
    /**
     * @return Id
     */
    public function id(): Id;
}
