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

/**
 * @internal
 */
final class Service
{
    private DependencyInterface $dependency;

    public function __construct(DependencyInterface $dependency)
    {
        $this->dependency = $dependency;
    }

    public function getDependency(): DependencyInterface
    {
        return $this->dependency;
    }
}
