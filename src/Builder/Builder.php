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

namespace Jascha030\DI\Builder;

use Closure;
use InvalidArgumentException;
use Jascha030\DI\BuilderInterface;
use Jascha030\DI\ContainerConfigInterface;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;

use function sprintf;

class Builder implements BuilderInterface
{
    private Closure $factory;

    /**
     * @throws ReflectionException
     */
    public function __construct(callable $callable)
    {
        $this->setFactory(
            $callable instanceof Closure
                ? $callable
                : Closure::fromCallable($callable)
        );
    }

    public function __invoke(ContainerConfigInterface $config): ContainerInterface
    {
        return ($this->factory)($config);
    }

    /**
     * @throws ReflectionException
     */
    private function setFactory(Closure $factory): void
    {
        $reflection = new ReflectionFunction($factory);

        if ($reflection->getNumberOfParameters() > 1) {
            throw self::invalidArgumentException();
        }

        $params = $reflection->getParameters();
        $param  = reset($params);

        if (false !== $param) {
            $type = $param->getType();

            if (null !== $type && $type->isBuiltin()) {
                throw self::invalidArgumentException();
            }

            if (null === $type) {
                throw self::invalidArgumentException();
            }

            // @phpstan-ignore-next-line
            $type = new ReflectionClass($type->getName());

            if (
                ! $type->isSubclassOf(ContainerConfigInterface::class)
                && ContainerConfigInterface::class !== $type->getName()
            ) {
                throw self::invalidArgumentException();
            }
        }

        $this->factory = $factory;
    }

    private static function invalidArgumentException(): InvalidArgumentException
    {
        return new InvalidArgumentException(
            sprintf('Factory callable should take 1 argument of type :%s.', ContainerConfigInterface::class)
        );
    }
}
