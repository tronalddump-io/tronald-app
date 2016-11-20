<?php

/**
 * SlackProvider.php - created 20 Nov 2016 18:32:10
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
use Tronald\Lib\Slack;

/**
 *
 * SlackProvider
 *
 * @package Tronal\App\Web
 *
 */
class SlackProvider implements ServiceProviderInterface
{
    /**
     *
     * {@inheritDoc}
     * @see \Pimple\ServiceProviderInterface::register()
     */
    public function register(Container $app)
    {
        $app['slack'] = function () use ($app) {
            return [
                'command_parser'     => new Slack\CommandParser($app['broker']['quote']),
                'verification_token' => getenv('SLACK_VERIFICATION_TOKEN') ?: null
            ];
        };
    }
}
