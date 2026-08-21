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

namespace Jascha030\DI\Exception;

use Exception;
use Psr\Container\ContainerExceptionInterface;
use Throwable;

use function sprintf;

class ContainerLookupException extends Exception implements ContainerExceptionInterface
{
    public function __construct(string $id, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Error while retrieving entry with ID: "%s".', $id),
            $code,
            $previous
        );
    }
}
