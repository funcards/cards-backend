<?php

declare(strict_types=1);

namespace FC\User\Infrastructure\RefreshToken;

use FC\User\Application\RefreshToken\Exception\InvalidRefreshTokenException;
use FC\User\Application\RefreshToken\RefreshTokenService;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Uid\Uuid;

final class Psr16RefreshTokenService implements RefreshTokenService
{
    private const KEY_PREFIX = 'refresh';

    /**
     * @param CacheInterface $cache
     * @param \DateInterval $ttl
     */
    public function __construct(private CacheInterface $cache, private \DateInterval $ttl)
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
            throw InvalidRefreshTokenException::create();
        }

        return $token;
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $refreshToken, string $payload): void
    {
        $this->cache->set($this->key($refreshToken), $payload, $this->ttl);
    }

    /**
     * {@inheritDoc}
     */
    public function delete(string $refreshToken): void
    {
        $this->cache->delete($this->key($refreshToken));
    }

    /**
     * @param string $refreshToken
     * @return string
     */
    private function key(string $refreshToken): string
    {
        return self::KEY_PREFIX . $refreshToken;
    }
}
