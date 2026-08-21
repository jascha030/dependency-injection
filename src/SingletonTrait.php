<?php

/*
 * This file is part of the jascha030/dependency-injection package.
 *
 * (c) Jascha van Aalst <contact@jaschavanaalst.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Jascha030\DI;

use LogicException;

/**
 * Singleton implementation as trait.
 *
 * Implementing class is open to decide on constructor visibility, but declares a signature without params.
 */
trait SingletonTrait
{
    private static ?self $instance = null;

    public static function getInstance(): self
    {
        return self::$instance ??= new static();
    }

    /**
     * Specifies constructor signature, but leaves visibility open to interpretation.
     *
     * @noinspection PhpMissingVisibilityInspection
     * @noinspection AccessModifierPresentedInspection
     */
    abstract public function __construct();

    /**
     * Prevent serialization.
     *
     * @phpstan-return array|never-return
     *
     * @throws LogicException
     */
    final public function __serialize(): array
    {
        throw new LogicException('Cannot serialize Singleton instance: ' . static::class);
    }

    /**
     * Prevent deserialization.
     *
     * @return void|never
     *
     * @throws LogicException
     */
    final public function __unserialize(array $data): void
    {
        throw new LogicException('Cannot deserialize singleton instance: ' . static::class);
    }

    /**
     * Prevent wakeup.
     *
     * @return void|never
     *
     * @throws LogicException
     */
    final public function __wakeup(): void
    {
        throw new LogicException('Cannot wakeup singleton instance: ' . static::class);
    }
}
