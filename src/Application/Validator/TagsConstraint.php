<?php

declare(strict_types=1);

namespace FC\Application\Validator;

use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Uuid;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final class TagsConstraint extends All
{
    public function __construct(array $groups = null, mixed $payload = null)
    {
        parent::__construct([new Uuid(versions: [Uuid::V4_RANDOM])], $groups, $payload);
    }
}
