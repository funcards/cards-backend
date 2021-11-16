<?php

namespace FC\Shared\Domain;

use FC\Shared\Domain\ValueObject\Id;

interface Entity extends \Stringable
{
    /**
     * @return Id
     */
    public function id(): Id;
}
