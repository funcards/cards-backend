<?php

declare(strict_types=1);

namespace FC\Application\Validator;

use FC\Domain\ValueObject\Role;
use FC\Domain\ValueObject\Roles;
use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final class RolesConstraint extends Constraint
{
    public string $message = 'Role "{{ role }}" is not valid.';

    /**
     * @param mixed|null $options
     * @param string|null $message
     * @param Roles $notValid
     * @param array<string>|null $groups
     * @param mixed|null $payload
     */
    public function __construct(
        mixed $options = null,
        string $message = null,
        public readonly Roles $notValid = new Roles([
            Role::User,
            Role::ApiUser,
            Role::Admin,
            Role::SuperAdmin,
            Role::AllowedToSwitch,
            Role::BoardOwner,
        ]),
        array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct($options, $groups, $payload);

        $this->message = $message ?? $this->message;
    }
}
