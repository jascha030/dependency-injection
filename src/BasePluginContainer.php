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

use Jascha030\DI\Builder\Builder;
use Psr\Container\ContainerInterface;

abstract class BasePluginContainer implements ContainerInterface
{
    use ContainerProxyTrait;
    use SingletonTrait;

    private ContainerInterface $container;

    private function __construct()
    {
        $builder         = $this->getBuilder();
        $this->container = $builder($this->getConfig());
    }

    /**
     * @return callable(ContainerConfigInterface): ContainerInterface
     */
    abstract protected function getBuilderFactory(): callable;

    abstract protected function getConfig(): ContainerConfigInterface;

    protected function getInnerContainer(): ContainerInterface
    {
        return $this->container;
    }

    protected function getBuilder(): BuilderInterface
    {
        return new Builder($this->getBuilderFactory());
    }
}
