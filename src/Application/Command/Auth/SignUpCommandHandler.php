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
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly PasswordHasherInterface $passwordHasher,
        private readonly AuthSessionServiceInterface $authSessionService,
        private readonly EventBus $eventBus,
    ) {
    }

    /**
     * @throws \Throwable
     */
    public function __invoke(SignUpCommand $command): Tokens
    {
        do {
            $hashed = $this->passwordHasher->hash($command->password);
        } while ($this->passwordHasher->needsRehash($hashed));

        $user = User::create(
            UserId::random(),
            UserName::fromString($command->name),
            UserEmail::fromString($command->email),
            UserPassword::fromString($hashed),
            Roles::from(...$command->roles),
        );

        $this->userRepository->save($user);
        $this->eventBus->publish(...$user->releaseEvents());

        return $this->authSessionService->newSession($user->id()->asString());
    }
}
