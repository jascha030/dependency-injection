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

namespace Jascha030\DI\Config;

use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Throwable;

use function array_filter;
use function count;

/**
 * @internal
 */
#[CoversClass(ContainerConfig::class)]
#[CoversClass(ContainerConfigResolver::class)]
final class ContainerConfigResolverTest extends TestCase
{
    /**
     * @depends testGetConfig
     */
    public function testDefinitionsAreWrappedInClosures(): void
    {
        $config = $this->getConfigResolver()->getConfig();

        self::assertCount(
            count($config->getDefinitions()),
            array_filter($config->getDefinitions(), static fn ($item) => $item instanceof Closure)
        );
    }

    /**
     * @depends testConstruct
     */
    public function testGetConfig(): void
    {
        $config = $this->getConfigResolver()->getConfig();

        self::assertInstanceOf(ContainerConfig::class, $config);
    }

    public function testConstruct(): void
    {
        self::assertInstanceOf(ContainerConfigResolver::class, $this->getConfigResolver());
    }

    private function getConfigResolver(): ContainerConfigResolver
    {
        try {
            return new ContainerConfigResolver(__DIR__ . '/../Fixtures/config');
        } catch (Throwable $e) {
            self::fail($e->getMessage());
        }
    }
}
