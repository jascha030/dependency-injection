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

trait ConfigResolverTrait
{
    protected function getConfig(): ContainerConfigInterface
    {
        return $this->getConfigResolver()->getConfig();
    }

    abstract protected function getConfigResolver(): ContainerConfigResolver;
}
