<?php

declare(strict_types=1);

namespace FC\Application\Validator;

use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Uuid;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final class AllUuidConstraint extends All
{
    public function __construct(array $groups = null, mixed $payload = null)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function validatedBy(): string
    {
        return All::class.'Validator';
    }
}
