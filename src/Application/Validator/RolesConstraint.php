<?php

declare(strict_types=1);

namespace FC\Application\Validator;

use FC\Domain\ValueObject\Role;
use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final class RolesConstraint extends Constraint
{
    public string $message = 'Role "{{ role }}" is not valid.';

    /**
     * @var array<string>
     */
    public array $notValid = [
        Role::USER,
        Role::API_USER,
        Role::ADMIN,
        Role::SUPER_ADMIN,
        Role::ALLOWED_TO_SWITCH,
        Role::BOARD_OWNER,
    ];

    /**
     * @param mixed|null $options
     * @param string|null $message
     * @param array<string>|null $notValid
     * @param array<string>|null $groups
     * @param mixed|null $payload
     */
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
