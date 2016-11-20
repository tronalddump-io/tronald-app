<?php

/**
 * ServiceProvider.php - created Mar 6, 2016 3:03:18 PM
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\App\Api\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tronald\Lib;

/**
 *
 * ServiceProvider
 *
 * @package Tronal\App\Web
 *
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     *
     * {@inheritDoc}
     * @see \Pimple\ServiceProviderInterface::register()
     */
    public function register(Container $container)
    {
        $container['broker'] = function () use ($container) {
            return [
                'author'       => new Lib\Broker\AuthorBroker(
                    $container['database'], $container['entity_factory']
                ),
                'quote'        => new Lib\Broker\QuoteBroker(
                    $container['database'], $container['entity_factory']
                ),
                'quote_source' => new Lib\Broker\QuoteSourceBroker(
                    $container['database'], $container['entity_factory']
                )
            ];
        };

        $container['database'] = function () use ($container) {
            return new Lib\Database($container['config']['database_url']);
        };

        $container['entity_factory'] = function () use ($container) {
            return new Lib\Entity\Factory();
        };
    }
}
