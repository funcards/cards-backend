<?php

declare(strict_types=1);

namespace FC\Infrastructure\Auth\JWT;

use FC\Application\Auth\JWT\Exception\ExpiredTokenException;
use FC\Application\Auth\JWT\Exception\InvalidTokenException;
use FC\Application\Auth\JWT\JWTEncoder;
use Lcobucci\Clock\Clock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Token\RegisteredClaims;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;

final class LcobucciJWTEncoder implements JWTEncoder
{
    private const MAP = [
        RegisteredClaims::AUDIENCE => 'permittedFor',
        RegisteredClaims::EXPIRATION_TIME => 'expiresAt',
        RegisteredClaims::ID => 'identifiedBy',
        RegisteredClaims::ISSUED_AT => 'issuedAt',
        RegisteredClaims::ISSUER => 'issuedBy',
        RegisteredClaims::NOT_BEFORE => 'canOnlyBeUsedAfter',
        RegisteredClaims::SUBJECT => 'relatedTo',
    ];

    public function __construct(private readonly Configuration $configuration, private readonly Clock $clock, private readonly \DateInterval $ttl)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function encode(array $payload, array $headers = []): string
    {
        $builder = $this->configuration->builder();

        foreach ($headers as $key => $value) {
            $builder->withHeader($key, $value);
        }

        $now = $this->clock->now();

        if (!\array_key_exists(RegisteredClaims::ISSUED_AT, $payload)) {
            $payload[RegisteredClaims::ISSUED_AT] = $now;
        }

        if (!\array_key_exists(RegisteredClaims::NOT_BEFORE, $payload)) {
            $payload[RegisteredClaims::NOT_BEFORE] = clone $payload[RegisteredClaims::ISSUED_AT];
        }

        if (!\array_key_exists(RegisteredClaims::EXPIRATION_TIME, $payload)) {
            $payload[RegisteredClaims::EXPIRATION_TIME] = $payload[RegisteredClaims::ISSUED_AT]->add($this->ttl);
        }

        foreach ($payload as $key => $value) {
            if (isset(self::MAP[$key])) {
                \call_user_func_array([$builder, self::MAP[$key]], \is_array($value) ? $value : [$value]);
            } else {
                $builder->withClaim($key, $value);
            }
        }

        $token = $builder->getToken($this->configuration->signer(), $this->configuration->signingKey());

        return $token->toString();
    }

    /**
     * {@inheritDoc}
     */
    public function decode(string $token): array
    {
        try {
            $token = $this->configuration->parser()->parse($token);

            \assert($token instanceof UnencryptedToken);

            $constraints = [
                ...$this->configuration->validationConstraints(),
                new StrictValidAt($this->clock, $this->ttl),
                new SignedWith($this->configuration->signer(), $this->configuration->verificationKey()),
            ];

            $this->configuration->validator()->assert($token, ...$constraints);

            return $token->claims()->all();
        } catch (\Throwable $e) {
            if ($e instanceof RequiredConstraintsViolated && \str_contains($e->getMessage(), 'expired')) {
                throw ExpiredTokenException::new($e);
            }
            throw InvalidTokenException::new($e);
        }
    }
}
