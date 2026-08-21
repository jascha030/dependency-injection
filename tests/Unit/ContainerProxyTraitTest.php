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
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertTrue;

/**
 * @internal
 */
#[CoversClass(BuilderClass::class)]
#[CoversClass(ContainerConfig::class)]
#[CoversTrait(ContainerProxyTrait::class)]
final class ContainerProxyTraitTest extends TestCase
{
    use TestServiceProviderTrait;

    /**
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     * @throws NotFoundExceptionInterface
     */
    public function testGet(): void
    {
        assertInstanceOf(DependencyInterface::class, $this->mock()->get(DependencyInterface::class));
    }

    /**
     * @throws ReflectionException
     */
    public function testHas(): void
    {
        assertTrue($this->mock()->has(DependencyInterface::class));
    }

    /**
     * @throws ReflectionException
     */
    private function mock(): ContainerInterface
    {
        $container = $this->getBuilder()($this->getContainerConfig());

        return new class ($container) implements ContainerInterface {
            use ContainerProxyTrait;

            public function __construct(private ContainerInterface $container)
            {
            }

            protected function getInnerContainer(): ContainerInterface
            {
                return $this->container;
            }
        };
    }
}
