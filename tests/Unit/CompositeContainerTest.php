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
use Jascha030\DI\Config\ContainerConfig;
use Jascha030\DI\Fixtures\DependencyInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use ReflectionException;

use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertTrue;

/**
 * @internal
 */
#[CoversClass(BuilderClass::class)]
#[CoversClass(CompositeContainer::class)]
#[CoversClass(ContainerConfig::class)]
final class CompositeContainerTest extends TestCase
{
    use TestServiceProviderTrait;

    /**
     * @throws ReflectionException
     */
    public function testHas(): void
    {
        assertTrue($this->getContainer()->has(DependencyInterface::class));
    }

    /**
     * @throws ReflectionException
     */
    public function testConstruct(): void
    {
        assertInstanceOf(ContainerInterface::class, $this->getContainer());
    }

    /**
     * @throws ReflectionException
     */
    private function getContainer(): ContainerInterface
    {
        $containers = [$this->getBuilder()($this->getContainerConfig())];

        return new CompositeContainer($containers);
    }
}
