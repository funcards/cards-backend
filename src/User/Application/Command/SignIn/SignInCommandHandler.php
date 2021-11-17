<?php

declare(strict_types=1);

namespace FC\User\Application\Command\SignIn;

use FC\Shared\Application\Command\CommandHandler;
use FC\User\Application\Auth\AuthSession;
use FC\User\Application\Auth\AuthSessionServiceInterface;
use FC\User\Domain\Exception\BadCredentialsException;
use FC\User\Domain\UserEmail;
use FC\User\Domain\UserRepository;
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
     * @return AuthSession
     * @throws \Throwable
     */
    public function __invoke(SignInCommand $command): AuthSession
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
