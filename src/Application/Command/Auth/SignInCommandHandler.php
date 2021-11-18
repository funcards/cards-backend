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
    /**
     * @param UserRepository $userRepository
     * @param PasswordHasherInterface $passwordHasher
     * @param AuthSessionServiceInterface $authSessionService
     */
    public function __construct(
        private UserRepository $userRepository,
        private PasswordHasherInterface $passwordHasher,
        private AuthSessionServiceInterface $authSessionService,
    ) {
    }

    /**
     * @param SignInCommand $command
     * @return Tokens
     * @throws \Throwable
     */
    public function __invoke(SignInCommand $command): Tokens
    {
        $email = UserEmail::fromString(\strtolower($command->getEmail()));

        try {
            $user = $this->userRepository->get($email);

            if (!$this->passwordHasher->verify($user->password()->asString(), $command->getPassword())) {
                throw BadCredentialsException::new('Invalid password.');
            }
        } catch (\Throwable $e) {
            throw BadCredentialsException::new('Invalid credentials.', $e);
        }

        return $this->authSessionService->newSession($user->id()->asString());
    }
}
