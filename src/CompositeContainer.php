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
use Psr\Container\ContainerInterface;

use function is_array;

class CompositeContainer implements ContainerInterface
{
    /**
     * @var array<string|class-string, int>
     */
    private array $lookupTable = [];

    /**
     * @var array<int, ContainerInterface>
     */
    private array $containers;

    /**
     * @param iterable<int|string, ContainerInterface> $containers
     */
    public function __construct(iterable $containers)
    {
        $this->containers = array_values(
            ! is_array($containers)
                ? iterator_to_array($containers)
                : $containers
        );

        $this->containers = array_filter(
            $this->containers,
            // @phpstan-ignore-next-line
            static fn ($item): bool => is_subclass_of($item, ContainerInterface::class)
        );
    }

    /**
     * @template T of object
     *
     * @param class-string<T>|string $id
     *
     * @return ($id is class-string<T> ? T : mixed)
     *
     * @throws \Psr\Container\ContainerExceptionInterface|\Psr\Container\NotFoundExceptionInterface
     * @throws ContainerEntryNotFoundException|ContainerLookupException
     */
    public function get(string $id)
    {
        if ($this->has($id)) {
            if (! isset($this->containers[$this->lookupTable[$id]])) {
                throw new ContainerLookupException($id);
            }

            return $this->containers[$this->lookupTable[$id]]->get($id);
        }

        throw new ContainerEntryNotFoundException($id);
    }

    /**
     * @param string|class-string $id
     *
     * @noinspection MultipleReturnStatementsInspection
     */
    public function has(string $id): bool
    {
        if (isset($this->lookupTable[$id])) {
            return true;
        }

        foreach ($this->containers as $index => $container) {
            if (! $container->has($id)) {
                continue;
            }

            $this->lookupTable[$id] = $index;

            return true;
        }

        return false;
    }
}
