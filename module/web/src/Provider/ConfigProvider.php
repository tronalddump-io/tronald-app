<?php

/**
 * ConfigProvider.php - created 12 Nov 2016 14:24:07
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\App\Web\Provider;

use DirectoryIterator;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 *
 * ConfigProvider
 *
 * @package Tronal\App\Web
 *
 */
class ConfigProvider implements ServiceProviderInterface
{
    /**
     *
     * {@inheritDoc}
     * @see \Pimple\ServiceProviderInterface::register()
     */
    public function register(Container $app)
    {
        $app['config'] = function () use ($app) {
            $config = [];
            $path   = sprintf('%s/env/%s/', CONFIG_PATH, $app['application_env']);

            foreach (new DirectoryIterator($path) as $fileInfo) {
                if($fileInfo->isDot()) {
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
