<?php

namespace FC\Domain\Aggregate;

use FC\Domain\ValueObject\Id;

interface Entity extends \Stringable
{
    public function id(): Id;
}
