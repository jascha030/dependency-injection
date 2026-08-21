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

use Jascha030\DI\ServiceProvider\ServiceProviderInterface;
use Psr\Container\ContainerInterface;

class MockServiceProvider implements ServiceProviderInterface
{
    public function getFactories(): iterable
    {
        return [
            'dependency.id'            => static fn (): string => 'test',
            DependencyInterface::class => static fn (ContainerInterface $container): DependencyInterface => new Dependency($container->get('dependency.id')),
            Service::class             => static fn (ContainerInterface $container): Service => new Service($container->get(DependencyInterface::class)),
        ];
    }

    public function getExtensions(): iterable
    {
        return [];
    }
}
