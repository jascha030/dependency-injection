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

use Jascha030\DI\ServiceProvider\ServiceProviderInterface;

/**
 * @phpstan-type CallableOrClosure callable|\Closure
 * @phpstan-type CallableFactoryDefinition (CallableOrClosure(\Psr\Container\ContainerInterface=): mixed)
 * @phpstan-type ServiceProviderDefinition class-string<ServiceProviderInterface>|ServiceProviderInterface
 */
interface ContainerConfigInterface
{
    /**
     * Add factories set in the container using iterables/arrays.
     *
     * @return iterable<string|class-string, CallableFactoryDefinition>
     */
    public function getDefinitions(): iterable;

    /**
     * Add factories set in the container using classes implementing ServiceProviderInterface.
     *
     * @return iterable<ServiceProviderDefinition>
     *
     * @see ServiceProviderInterface
     */
    public function getProviders(): iterable;
}
