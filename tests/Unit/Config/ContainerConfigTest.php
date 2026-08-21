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
use Jascha030\DI\ContainerConfigInterface;
use Jascha030\DI\TestServiceProviderTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;

/**
 * @internal
 */
#[CoversClass(ContainerConfig::class)]
final class ContainerConfigTest extends TestCase
{
    use TestServiceProviderTrait;

    public function testCreate(): void
    {
        assertInstanceOf(
            ContainerConfigInterface::class,
            ContainerConfig::create()
        );
    }

    public function testDefinitions(): void
    {
        assertInstanceOf(
            Closure::class,
            ContainerConfig::create()
                ->withDefinitions(['test' => static fn (): string => 'value'])
                ->getDefinitions()['test']
        );
    }

    public function testProviders(): void
    {
        assertEquals(
            $this->getServiceProviders(),
            ContainerConfig::create()
                ->withProviders($this->getServiceProviders())
                ->getProviders()
        );
    }
}
