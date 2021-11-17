<?php

declare(strict_types=1);

namespace FC\User\Application\Command\Create;

use FC\Shared\Application\Command\CommandHandler;
use FC\Shared\Domain\Event\EventBus;
use FC\Shared\Domain\ValueObject\Roles;
use FC\Shared\Domain\ValueObject\User\UserId;
use FC\User\Application\Auth\AuthSession;
use FC\User\Application\Auth\AuthSessionServiceInterface;
use FC\User\Domain\User;
use FC\User\Domain\UserEmail;
use FC\User\Domain\UserName;
use FC\User\Domain\UserPassword;
use FC\User\Domain\UserRepository;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

final class CreateCommandHandler implements CommandHandler
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
     * @param CreateCommand $command
     * @return AuthSession
     * @throws \Throwable
     */
    public function __invoke(CreateCommand $command): AuthSession
    {
        do {
            $hashed = $this->passwordHasher->hash($command->getPassword());
        } while ($this->passwordHasher->needsRehash($hashed));

        $user = User::create(
            UserId::random(),
            UserName::fromString($command->getName()),
            UserEmail::fromString(\strtolower($command->getEmail())),
            UserPassword::fromString($hashed),
            Roles::fromString(...$command->getRoles()),
        );

        $this->userRepository->save($user);
        $this->eventBus->publish(...$user->releaseEvents());

        return $this->authSessionService->newSession($user->id()->asString());
    }
}
