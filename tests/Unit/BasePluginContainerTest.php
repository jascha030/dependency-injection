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
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(BasePluginContainer::class)]
#[CoversClass(BuilderClass::class)]
#[CoversTrait(ConfigResolverTrait::class)]
#[CoversClass(ContainerConfig::class)]
#[CoversClass(ContainerConfigResolver::class)]
final class BasePluginContainerTest extends TestCase
{
    public function testGet(): void
    {
        self::assertEquals('test', PluginContainer::getInstance()->get('test.value'));
    }
}
