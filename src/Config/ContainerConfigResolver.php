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

use Closure;
use Generator;
use InvalidArgumentException;
use Jascha030\DI\ContainerConfigResolverInterface;
use Jascha030\DI\ServiceProvider\ServiceProviderInterface;
use SplFileInfo;
use Throwable;

use function array_diff_key;
use function array_filter;
use function array_flip;
use function count;
use function is_array;
use function is_callable;
use function is_object;
use function is_string;
use function is_subclass_of;
use function scandir;
use function sprintf;

/**
 * @phpstan-import-type ServiceProviderDefinition from \Jascha030\DI\ContainerConfigInterface
 */
class ContainerConfigResolver implements ContainerConfigResolverInterface
{
    private ContainerConfig $config;

    private string $root;

    public function __construct(string $root)
    {
        if (! is_dir($root)) {
            throw new InvalidArgumentException(sprintf('Root path "%s" does not exist.', $root));
        }

        $this->root = $root;
    }

    public function getConfig(): ContainerConfig
    {
        if (! isset($this->config)) {
            $this->createConfig();
        }

        return $this->config;
    }

    /**
     * @return array<string, mixed>
     */
    private function resolve(): array
    {
        $files = scandir($this->root);

        $files = array_filter(array_map(
            function (string $path): ?SplFileInfo {
                $info = new SplFileInfo($this->root . '/' . $path);

                return str_contains($info->getBasename(), '.php')
                    ? $info
                    : null;
            },
            array_diff(
                false !== $files
                    ? $files
                    : [],
                ['.', '..']
            )
        ));

        if (0 === count($files)) {
            return [];
        }

        return array_merge_recursive(...(function () use ($files): Generator {
            foreach ($files as $file) {
                try {
                    yield (static fn (): array => require $file->getRealPath())();
                } /** @noinspection BadExceptionsProcessingInspection */
                catch (Throwable $e) {
                    yield [];
                }
            }
        })());
    }

    private function createConfig(): void
    {
        $config    = $this->resolve();
        $providers = [];

        if (isset($config['providers']) && is_array($config['providers'])) {
            $providers = $this->validateProviders($config['providers']);
        }

        $config = array_diff_key($config, array_flip(['providers']));

        $this->config = ContainerConfig::create()
            ->withProviders($providers)
            ->withDefinitions(array_map(
                [$this, 'wrap'],
                $config
            ));
    }

    /**
     * @return Closure(): mixed
     */
    private function wrap($item): Closure
    {
        return is_callable($item)
            ? Closure::fromCallable($item)
            : static fn () => $item;
    }

    /**
     * @param mixed[] $providers
     *
     * @return list<ServiceProviderDefinition>
     */
    private function validateProviders(array $providers): array
    {
        // @phpstan-ignore-next-line
        return array_filter($providers, static function ($item): bool {
            if (! is_string($item) && ! is_object($item)) {
                return false;
            }

            return is_subclass_of($item, ServiceProviderInterface::class);
        });
    }
}
