<?php

declare(strict_types=1);

namespace FC\Board\Domain\Exception;

use FC\Shared\Domain\Exception\NotFoundDomainException;

final class MemberNotFoundException extends NotFoundDomainException implements BoardException
{
}
