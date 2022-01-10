<?php

declare(strict_types=1);

namespace FC\Application\Command\Auth;

use FC\Application\Auth\Tokens;
use FC\Application\Auth\AuthSessionServiceInterface;
use FC\Application\Bus\Command\CommandHandler;
use FC\Domain\Aggregate\UserAggregate\UserEmail;
use FC\Domain\Aggregate\UserAggregate\UserRepository;
use FC\Domain\Exception\BadCredentialsException;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

final class SignInCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly PasswordHasherInterface $passwordHasher,
        private readonly AuthSessionServiceInterface $authSessionService,
    ) {
    }

    /**
     * @throws \Throwable
     */
    public function __invoke(SignInCommand $command): Tokens
    {
        $email = UserEmail::fromString($command->email);

        try {
            $user = $this->userRepository->get($email);

            if (!$this->passwordHasher->verify($user->password()->asString(), $command->password)) {
                throw BadCredentialsException::new('Invalid password.');
            }
        } catch (\Throwable $e) {
            throw BadCredentialsException::new('Invalid credentials.', $e);
        }

        return $this->authSessionService->newSession($user->id()->asString());
    }
}
