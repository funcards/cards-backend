<?php

declare(strict_types=1);

namespace FC\Application\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final class RolesConstraint extends Constraint
{
    public string $message = 'Role "{{ role }}" is not valid.';

    public array $notValid = [
        'ROLE_USER',
        'ROLE_API_USER',
        'ROLE_ADMIN',
        'ROLE_SUPER_ADMIN',
        'ROLE_ALLOWED_TO_SWITCH',
        'ROLE_BOARD_OWNER',
    ];

    public function __construct(
        mixed $options = null,
        string $message = null,
        array $notValid = null,
        array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct($options, $groups, $payload);

        $this->message = $message ?? $this->message;
        $this->notValid = $notValid ?? $this->notValid;
    }
}
