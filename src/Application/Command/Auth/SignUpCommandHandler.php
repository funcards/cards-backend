<?php

declare(strict_types=1);

namespace FC\Application\Command\Auth;

use FC\Application\Auth\Tokens;
use FC\Application\Auth\AuthSessionServiceInterface;
use FC\Application\Bus\Command\CommandHandler;
use FC\Application\Bus\Event\EventBus;
use FC\Domain\Aggregate\UserAggregate\User;
use FC\Domain\Aggregate\UserAggregate\UserEmail;
use FC\Domain\Aggregate\UserAggregate\UserId;
use FC\Domain\Aggregate\UserAggregate\UserName;
use FC\Domain\Aggregate\UserAggregate\UserPassword;
use FC\Domain\Aggregate\UserAggregate\UserRepository;
use FC\Domain\ValueObject\Roles;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

final class SignUpCommandHandler implements CommandHandler
{
    /**
     * @param UserRepository $userRepository
     * @param PasswordHasherInterface $passwordHasher
     * @param AuthSessionServiceInterface $authSessionService
     * @param EventBus $eventBus
     */
    public function __construct(
        private UserRepository $userRepository,
        private PasswordHasherInterface $passwordHasher,
        private AuthSessionServiceInterface $authSessionService,
        private EventBus $eventBus,
    ) {
    }

    /**
     * @param SignUpCommand $command
     * @return Tokens
     * @throws \Throwable
     */
    public function __invoke(SignUpCommand $command): Tokens
    {
        do {
            $hashed = $this->passwordHasher->hash($command->getPassword());
        } while ($this->passwordHasher->needsRehash($hashed));

        $user = User::create(
            UserId::random(),
            UserName::fromString($command->getName()),
            UserEmail::fromString($command->getEmail()),
            UserPassword::fromString($hashed),
            Roles::fromString(...$command->getRoles()),
        );

        $this->userRepository->save($user);
        $this->eventBus->publish(...$user->releaseEvents());

        return $this->authSessionService->newSession($user->id()->asString());
    }
}
