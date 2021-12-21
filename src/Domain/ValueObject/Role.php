<?php

declare(strict_types=1);

namespace FC\Domain\ValueObject;

use Assert\Assert;
use JetBrains\PhpStorm\Pure;

/**
 * @method static Role user()
 * @method static Role apiUser()
 * @method static Role admin()
 * @method static Role superAdmin()
 * @method static Role allowedToSwitch()
 *
 * @method static Role boardOwner()
 * @method static Role boardView()
 * @method static Role boardEdit()
 * @method static Role boardAddMember()
 * @method static Role boardRemoveMember()
 * @method static Role categoryView()
 * @method static Role categoryAdd()
 * @method static Role categoryEdit()
 * @method static Role categoryRemove()
 * @method static Role tagView()
 * @method static Role tagAdd()
 * @method static Role tagEdit()
 * @method static Role tagRemove()
 * @method static Role cardView()
 * @method static Role cardAdd()
 * @method static Role cardEdit()
 * @method static Role cardRemove()
 */
final class Role implements \Stringable
{
    public const USER = 'ROLE_USER';
    public const API_USER = 'ROLE_API_USER';
    public const ADMIN = 'ROLE_ADMIN';
    public const SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    public const ALLOWED_TO_SWITCH = 'ROLE_ALLOWED_TO_SWITCH';

    public const BOARD_OWNER = 'ROLE_BOARD_OWNER';
    public const BOARD_VIEW = 'ROLE_BOARD_VIEW';
    public const BOARD_EDIT = 'ROLE_BOARD_EDIT';
    public const BOARD_ADD_MEMBER = 'ROLE_BOARD_ADD_MEMBER';
    public const BOARD_REMOVE_MEMBER = 'ROLE_BOARD_REMOVE_MEMBER';
    public const CATEGORY_VIEW = 'ROLE_CATEGORY_VIEW';
    public const CATEGORY_ADD = 'ROLE_CATEGORY_ADD';
    public const CATEGORY_EDIT = 'ROLE_CATEGORY_EDIT';
    public const CATEGORY_REMOVE = 'ROLE_CATEGORY_REMOVE';
    public const TAG_VIEW = 'ROLE_TAG_VIEW';
    public const TAG_ADD = 'ROLE_TAG_ADD';
    public const TAG_EDIT = 'ROLE_TAG_EDIT';
    public const TAG_REMOVE = 'ROLE_TAG_REMOVE';
    public const CARD_VIEW = 'ROLE_CARD_VIEW';
    public const CARD_ADD = 'ROLE_CARD_ADD';
    public const CARD_EDIT = 'ROLE_CARD_EDIT';
    public const CARD_REMOVE = 'ROLE_CARD_REMOVE';

    /**
     * @var array<string, Role>
     */
    private static array $cache = [];

    public function __construct(private string $value)
    {
        Assert::that($value)->notEmpty()->startsWith('ROLE_');
    }

    /**
     * @param array<mixed> $arguments
     */
    public static function __callStatic(string $name, array $arguments): self
    {
        return self::cache()[$name];
    }

    public static function all(): Roles
    {
        return Roles::from(...self::values());
    }

    /**
     * @return array<Role>
     */
    public static function values(): array
    {
        return \array_values(self::cache());
    }

    public static function fromString(string $value): self
    {
        return self::cache()[self::toCamelCase($value)];
    }

    /**
     * @return static|null
     */
    public static function safeFromString(string $value): ?self
    {
        return self::cache()[self::toCamelCase($value)] ?? null;
    }

    public function asString(): string
    {
        return $this->value;
    }

    /**
     * {@inheritDoc}
     */
    #[Pure]
    public function __toString(): string
    {
        return $this->asString();
    }

    public function isEqualTo(?object $other): bool
    {
        return $other::class === $this::class && (string)$this === (string)$other;
    }

    /**
     * @return array<string, Role>
     */
    private static function cache(): array
    {
        if ([] === self::$cache) {
            $reflected = new \ReflectionClass(self::class);
            foreach ($reflected->getConstants() as $name => $value) {
                self::$cache[self::toCamelCase($name)] = new self($value);
            }
        }

        return self::$cache;
    }

    private static function toCamelCase(string $value): string
    {
        $value = \str_replace('role_', '', \strtolower($value));
        $value = \lcfirst(\ucwords($value, '_'));
        return \str_replace('_', '', $value);
    }
}
