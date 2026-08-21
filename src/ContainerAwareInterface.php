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

use Psr\Container\ContainerInterface;

/**
 * Interface ContainerAwareInterface.
 *
 * Specifies that an object can be injected with a container, through a setter.
 * Internal use or possible public access to outside classes of said container is not specified and thus open to interpretation.
 */
interface ContainerAwareInterface
{
    /**
     * Set the container instance.
     *
     * May be implemented fluid, in which case it returns itself.
     *
     * @return ContainerAwareInterface|void
     */
    public function setContainer(ContainerInterface $container);
}
