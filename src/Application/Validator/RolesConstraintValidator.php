<?php

declare(strict_types=1);

namespace FC\Application\Validator;

use FC\Domain\ValueObject\Role;
use FC\Domain\ValueObject\Roles;
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

        if (null !== ($role = $this->findNotValid($value, $rolesConstraint->notValid))) {
            $this->context->buildViolation($rolesConstraint->message)
                ->setParameter('{{ role }}', $role)
                ->addViolation();
        }
    }

    /**
     * @param array<string> $value
     */
    private function findNotValid(array $value, Roles $notValid): ?string
    {
        foreach ($value as $item) {
            $role = Role::tryFrom($item);

            if (!$role instanceof Role || $notValid->contains($role)) {
                return $item;
            }
        }

        return null;
    }
}
