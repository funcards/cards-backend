<?php

declare(strict_types=1);

namespace FC\Infrastructure\Auth\JWT;

use FC\Application\Auth\JWT\Exception\InvalidTokenException;
use FC\Application\Auth\JWT\JWTManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

final class JWTAuthenticator extends AbstractAuthenticator
{
    private const HEADER = 'Authorization';
    private const SCHEME = 'Bearer';

    public function __construct(private readonly JWTManagerInterface $jwtManager)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function supports(Request $request): ?bool
    {
        return null !== $this->extract($request);
    }

    /**
     * {@inheritDoc}
     */
    public function authenticate(Request $request): Passport
    {
        if (null === $token = $this->extract($request)) {
            throw InvalidTokenException::new(new \Exception('JWT token not found'));
        }

        $payload = $this->jwtManager->parse($token);
        $identifier = $this->jwtManager->getIdentifier($payload);

        $passport = new SelfValidatingPassport(new UserBadge($identifier));
        $passport->setAttribute('payload', $payload);
        $passport->setAttribute('token', $token);

        return $passport;
    }

    /**
     * {@inheritDoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $code = $exception->getCode() > 0 ? $exception->getCode() : Response::HTTP_UNAUTHORIZED;

        return new JsonResponse(['code' => $code, 'message' => $exception->getMessageKey()], $code);
    }

    private function extract(Request $request): ?string
    {
        if (!$request->headers->has(self::HEADER)) {
            return null;
        }

        $auth = \explode(' ', $request->headers->get(self::HEADER));

        if (2 == \count($auth) && 0 === \strcasecmp($auth[0], self::SCHEME)) {
            return $auth[1];
        }

        return null;
    }
}
