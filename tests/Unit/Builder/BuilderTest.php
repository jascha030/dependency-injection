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

namespace Jascha030\DI\Builder;

use Closure;
use InvalidArgumentException;
use Jascha030\DI\BuilderInterface;
use Jascha030\DI\Config\ContainerConfig;
use Jascha030\DI\ContainerConfigInterface;
use Jascha030\DI\ServiceProvider\ServiceProviderInterface;
use Jascha030\DI\TestServiceProviderTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use ReflectionException;

use function PHPUnit\Framework\assertInstanceOf;

/**
 * @internal
 */
#[CoversClass(Builder::class)]
#[CoversClass(ContainerConfig::class)]
final class BuilderTest extends TestCase
{
    use TestServiceProviderTrait;

    /**
     * @throws ReflectionException
     */
    public function testConstruct(): void
    {
        assertInstanceOf(BuilderInterface::class, $this->getBuilder());
    }

    /**
     * @depends testConstruct
     *
     * @throws ReflectionException
     */
    public function testInvoke(): void
    {
        $builder = $this->getBuilder();

        assertInstanceOf(ContainerInterface::class, $builder($this->getContainerConfig()));
    }

    /**
     * @throws ReflectionException
     */
    #[DataProvider('invalidFactoryProvider')]
    public function testThrowsOnInvalidClosureArgumentSignature(Closure $factory): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Builder($factory);
    }

    /**
     * @throws ReflectionException
     */
    public function testThrowsOnInvalidClosureArgummentNumber(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Builder(static fn (ContainerConfigInterface $config, string $ewa) => false);
    }

    /**
     * @return Closure[][]
     */
    public static function invalidFactoryProvider(): array
    {
        return [
            'Closure with scalar arg in signature'           => [static fn (string $param) => false],
            'Closure with array arg in signature'            => [static fn (array $param) => false],
            'Closure with non-container object in signature' => [static fn (ServiceProviderInterface $param) => false],
            'Closure with null in signature type'            => [static fn ($param) => false],
        ];
    }
}
