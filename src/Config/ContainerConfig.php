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

use Jascha030\DI\ContainerConfigInterface;

/**
 * @phpstan-import-type CallableFactoryDefinition from \Jascha030\DI\ContainerConfigInterface
 * @phpstan-import-type ServiceProviderDefinition from \Jascha030\DI\ContainerConfigInterface
 */
class ContainerConfig implements ContainerConfigInterface
{
    /**
     * @var array<string|class-string, CallableFactoryDefinition>
     */
    private array $definitions = [];

    /**
     * @var list<ServiceProviderDefinition>
     */
    private array $providers = [];

    private function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }

    /**
     * @param array<string|class-string,CallableFactoryDefinition> $definitions
     */
    public function withDefinitions(array $definitions): self
    {
        $new              = clone $this;
        $new->definitions = $definitions;

        return $new;
    }

    /**
     * @return array<string|class-string,CallableFactoryDefinition>
     */
    public function getDefinitions(): array
    {
        return $this->definitions;
    }

    /**
     * @param list<ServiceProviderDefinition> $providers Service providers to get definitions from
     */
    public function withProviders(array $providers): self
    {
        $new            = clone $this;
        $new->providers = $providers;

        return $new;
    }

    /**
     * @return list<ServiceProviderDefinition>
     */
    public function getProviders(): array
    {
        return $this->providers;
    }
}
