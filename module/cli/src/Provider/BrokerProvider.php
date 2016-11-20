<?php

/**
 * BrokerProvider.php - created 13 Nov 2016 22:12:55
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\App\Cli\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tronald\Lib;

/**
 *
 * BrokerProvider
 *
 * @package Tronal\App\Cli
 *
 */
class BrokerProvider implements ServiceProviderInterface
{
    /**
     *
     * {@inheritDoc}
     * @see \Pimple\ServiceProviderInterface::register()
     */
    public function register(Container $app)
    {
        $app['database'] = function () use ($app) {
            return new Lib\Database($app['config']['database_url']);
        };

        $app['broker'] = function () use ($app) {
            $entityFactory = new Lib\Entity\Factory();

            return [
                'author'       => new Lib\Broker\AuthorBroker($app['database'], $entityFactory),
                'quote'        => new Lib\Broker\QuoteBroker($app['database'], $entityFactory),
                'quote_source' => new Lib\Broker\QuoteSourceBroker($app['database'], $entityFactory)
            ];
        };
    }
}
