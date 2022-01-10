<?php

declare(strict_types=1);

namespace FC\Infrastructure\Auth\RefreshToken;

use FC\Application\Auth\RefreshToken\Exception\InvalidRefreshTokenException;
use FC\Application\Auth\RefreshToken\RefreshTokenService;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Uid\Uuid;

final class Psr16RefreshTokenService implements RefreshTokenService
{
    private const KEY_PREFIX = 'refresh';

    public function __construct(private readonly CacheInterface $cache, private readonly \DateInterval $ttl)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function generate(): string
    {
        return \str_replace('-', '', Uuid::v4()->toRfc4122());
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $refreshToken): string
    {
        if (null === $token = $this->cache->get($this->key($refreshToken))) {
            throw InvalidRefreshTokenException::new();
        }

        return $token;
    }

    public function set(string $refreshToken, string $payload): void
    {
        $this->cache->set($this->key($refreshToken), $payload, $this->ttl);
    }

    public function delete(string $refreshToken): void
    {
        $this->cache->delete($this->key($refreshToken));
    }

    private function key(string $refreshToken): string
    {
        return self::KEY_PREFIX . $refreshToken;
    }
}
