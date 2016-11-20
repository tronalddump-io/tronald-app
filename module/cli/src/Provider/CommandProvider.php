<?php

/**
 * CommandProvider.php - created 19 Nov 2016 22:29:26
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
use Tronald\App\Cli;
use Tronald\Lib;

/**
 *
 * CommandProvider
 *
 * @package Tronal\App\Cli
 *
 */
class CommandProvider implements ServiceProviderInterface
{

    /**
     *
     * {@inheritDoc}
     * @see \Pimple\ServiceProviderInterface::register()
     */
    public function register(Container $container)
    {
        $container->register(new \Gitory\PimpleCli\ServiceCommandServiceProvider());

        $container['quote.import.command'] = function () use ($container) {
            $importQuoteCommand = new \Tronald\App\Cli\Command\ImportQuoteCommand(
                new Lib\Entity\Factory(),
                $container['broker']['quote'],
                $container['broker']['quote_source']
            );

            $importQuoteCommand->setLogger($container['logger']);
            return $importQuoteCommand;
        };
    }
}
