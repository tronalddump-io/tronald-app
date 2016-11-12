<?php

/**
 * BrokerProvider.php - created 13 Nov 2016 12:41:07
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\App\Web\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tronald\Lib;

/**
 *
 * BrokerProvider
 *
 * @package Tronal\App\Web
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
        $app['broker'] = function () use ($app) {
            $database      = new Lib\Database($app['config']['database_url']);
            $entityFactory = new Lib\Entity\Factory();

            $broker  = [
            ];

            return [
                'author'       => new Lib\Broker\AuthorBroker($database, $entityFactory),
                'quote'        => new Lib\Broker\QuoteBroker($database, $entityFactory),
                'quote_source' => new Lib\Broker\QuoteSourceBroker($database, $entityFactory)
            ];
        };
    }
}
