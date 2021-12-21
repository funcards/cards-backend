<?php

declare(strict_types=1);

namespace FC\Application\Validator;

use FC\Domain\ValueObject\Role;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class RolesConstraintValidator extends ConstraintValidator
{
    /**
     * {@inheritDoc}
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (null === $value) {
            return;
        }

        if (!\is_array($value)) {
            throw new UnexpectedValueException($value, 'array');
        }

        if ([] === $value) {
            $this->context->buildViolation('Empty roles.')->addViolation();
            return;
        }

        /** @var RolesConstraint $rolesConstraint */
        $rolesConstraint = $constraint;

        foreach ($value as $role) {
            if (\in_array(\strtoupper($role), $rolesConstraint->notValid, true)
                || null === Role::safeFromString($role)) {
                $this->context->buildViolation($rolesConstraint->message)
                    ->setParameter('{{ role }}', $role)
                    ->addViolation();
                break;
            }
        }
    }
}
