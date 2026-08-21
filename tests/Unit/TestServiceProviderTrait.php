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

use DI\ContainerBuilder;
use Generator;
use Jascha030\DI\Builder\Builder;
use Jascha030\DI\Config\ContainerConfig;
use Jascha030\DI\Fixtures\MockServiceProvider;
use Jascha030\DI\ServiceProvider\ServiceProviderInterface;
use Psr\Container\ContainerInterface;
use ReflectionException;

trait TestServiceProviderTrait
{
    /**
     * @return list<class-string<ServiceProviderInterface>>
     */
    private function getServiceProviders(): array
    {
        return [MockServiceProvider::class];
    }

    private function getContainerConfig(): ContainerConfigInterface
    {
        return ContainerConfig::create()->withProviders($this->getServiceProviders());
    }

    /**
     * @throws ReflectionException
     */
    public function getBuilder(): Builder
    {
        return new Builder(static function (ContainerConfigInterface $config): ContainerInterface {
            return new ContainerBuilder()
                ->addDefinitions(iterator_to_array((static function () use ($config): Generator {
                    foreach ($config->getProviders() as $class) {
                        /**
                         * @var class-string<ServiceProviderInterface> $class
                         * @var ServiceProviderInterface               $provider
                         */
                        $provider = new $class();
                        yield from $provider->getFactories();
                    }
                })()))
                ->build();
        });
    }
}
