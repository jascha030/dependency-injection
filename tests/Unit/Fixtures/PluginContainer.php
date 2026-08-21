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

namespace Jascha030\DI\Fixtures;

use DI\ContainerBuilder;
use InvalidArgumentException;
use Jascha030\DI\BasePluginContainer;
use Jascha030\DI\Config\ConfigResolverTrait;
use Jascha030\DI\Config\ContainerConfigResolver;
use Jascha030\DI\ContainerConfigInterface;
use Jascha030\DI\ServiceProvider\ServiceProviderInterface;
use Psr\Container\ContainerInterface;

use function array_map;
use function array_merge;
use function class_exists;
use function is_string;
use function is_subclass_of;

/**
 * @internal
 */
final class PluginContainer extends BasePluginContainer
{
    use ConfigResolverTrait;

    protected function getBuilderFactory(): callable
    {
        return fn (ContainerConfigInterface $config): ContainerInterface => new ContainerBuilder() // Just how I like it, nice and confusing.
            ->addDefinitions(array_merge(
                array_merge(...array_map(
                    fn ($p): array => $this->extract($p),
                    (array) $config->getProviders()
                )),
                (array) $config->getDefinitions()
            ))->build();
    }

    protected function getConfigResolver(): ContainerConfigResolver
    {
        return new ContainerConfigResolver(__DIR__ . '/config');
    }

    /**
     * @param ServiceProviderInterface|string|class-string $provider
     *
     * @return mixed[]
     */
    private function extract($provider): array
    {
        if (is_string($provider) && ! class_exists($provider)) {
            throw new InvalidArgumentException('Invalid service provider string: ' . $provider);
        }

        $provider = is_string($provider)
            ? new $provider()
            : $provider;

        if (! is_subclass_of($provider, ServiceProviderInterface::class)) {
            throw new InvalidArgumentException('Invalid service provider of class: ' . $provider::class);
        }

        return (array) $provider->getFactories();
    }
}
