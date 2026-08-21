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

use Jascha030\DI\Builder\Builder as BuilderClass;
use Jascha030\DI\Config\ConfigResolverTrait;
use Jascha030\DI\Config\ContainerConfig;
use Jascha030\DI\Config\ContainerConfigResolver;
use Jascha030\DI\Fixtures\PluginContainer;
use LogicException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @internal
 */
#[CoversClass(BasePluginContainer::class)]
#[CoversClass(BuilderClass::class)]
#[CoversTrait(ConfigResolverTrait::class)]
#[CoversClass(ContainerConfig::class)]
#[CoversClass(ContainerConfigResolver::class)]
#[CoversTrait(SingletonTrait::class)]
final class SingletonTraitTest extends TestCase
{
    /**
     * @depends testGetInstance
     */
    public function testThrowsOnSerialization(): void
    {
        $this->expectException(LogicException::class);

        serialize(PluginContainer::getInstance());
    }

    /**
     * @depends testGetInstance
     */
    public function testThrowsOnDeserialization(): void
    {
        $this->expectException(LogicException::class);

        PluginContainer::getInstance()->__wakeup();
    }

    public function testGetInstance(): void
    {
        self::assertInstanceOf(ContainerInterface::class, PluginContainer::getInstance());

        self::assertEquals(PluginContainer::getInstance(), PluginContainer::getInstance());
    }
}
