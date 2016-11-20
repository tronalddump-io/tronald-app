<?php

/**
 * ConfigProvider.php - created 19 Nov 2016 22:11:07
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\App\Cli\Provider;

use DirectoryIterator;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 *
 * ConfigProvider
 *
 * @package Tronal\App\Cli
 *
 */
class ConfigProvider implements ServiceProviderInterface
{
    /**
     *
     * {@inheritDoc}
     * @see \Pimple\ServiceProviderInterface::register()
     */
    public function register(Container $container)
    {
        $container['config'] = function () use ($container) {
            $config = [];
            $path   = sprintf(
                '%s/env/%s/',
                CONFIG_PATH,
                \Tronald\Lib\Util::getEnvOrDefault('APPLICATION_ENV', 'prod')
            );

            foreach (new DirectoryIterator($path) as $fileInfo) {
                if ($fileInfo->isDot()) {
                    continue;
                }

                $config += include $fileInfo->getRealPath();
            }

            $config += [
                'database_url' => getenv('DATABASE_URL') ?: null,
            ];

            return $config;
        };
    }
}
