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

use Jascha030\DI\Exception\ContainerEntryNotFoundException;
use Jascha030\DI\Exception\ContainerLookupException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

trait ContainerProxyTrait
{
    /**
     * @template T of object
     *
     * @param string|class-string<T> $id
     *
     * @return ($id is class-string<T> ? T : mixed)
     *
     * @throws ContainerEntryNotFoundException|ContainerLookupException
     * @throws ContainerExceptionInterface|NotFoundExceptionInterface
     *
     * @see ContainerInterface::get()
     */
    public function get(string $id)
    {
        return $this->getInnerContainer()->get($id);
    }

    /**
     * @see ContainerInterface::has()
     */
    public function has(string $id): bool
    {
        return $this->getInnerContainer()->has($id);
    }

    /**
     * Return the delegate container instance.
     */
    abstract protected function getInnerContainer(): ContainerInterface;
}
